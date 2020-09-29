<?php

namespace App\Http\Controllers\Api\V1\Log;

use App\Domain\Listener\ListenerPatternMatcher;
use App\Domain\Project\ProjectEntity;
use App\Handler\LogHandlerContainer;
use App\Http\ResponseError;
use App\Repository\Repository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Psr\Log\LogLevel;

class LogController
{
    public function single($uuid, Request $request, Repository $repository, LogHandlerContainer $handlerContainer, ListenerPatternMatcher $matcher)
    {
        $validator = Validator::make($request->all(), [
            'level' => ['required', Rule::in([LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE, LogLevel::WARNING, LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY])],
            'message' => ['required']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $project = $repository->project()->getByUuid($uuid);
        if ($project == null) {
            return response([], 404);
        }
        $listners = $repository->listener()->getAllForProject($project->getId());

        $this->proccessBatch(
            [$request->all()],
            $listners,
            $project,
            $matcher,
            $handlerContainer
        );

        return response([], 200);
    }

    public function batch($uuid, Request $request, Repository $repository, LogHandlerContainer $handlerContainer, ListenerPatternMatcher $matcher)
    {
        $validator = Validator::make($request->all(), [
            '*.level' => ['required', Rule::in([LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE, LogLevel::WARNING, LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY])],
            '*.message' => ['required']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $project = $repository->project()->getByUuid($uuid);
        if ($project == null) {
            return response([], 404);
        }
        $listners = $repository->listener()->getAllForProject($project->getId());

        $this->proccessBatch(
            $request->all(),
            $listners,
            $project,
            $matcher,
            $handlerContainer
        );

        return response([], 200);
    }

    private function proccessBatch($events, $listners, ProjectEntity $project, ListenerPatternMatcher $matcher, LogHandlerContainer $handlerContainer)
    {
        foreach ($events as $event) {
            $matched = $matcher->match($event['level'], $listners);
            foreach ($matched as $matchedListener) {
                $logHandler = $handlerContainer->get($matchedListener->getHandlerSlug());
                if ($logHandler == null) {
                    throw new Exception("Cannot process log, because log handler does not exists: " . $matchedListener->getHandlerSlug());
                }
                try {
                    $logHandler->handle(
                        $event,
                        $project,
                        $matchedListener->getHandlerValues()
                    );
                } catch (Exception $ex) {
                    Log::warning("Log handler finnished with error: " . $ex->getMessage() . ". Skipping handler.", [
                        'code' => $ex->getCode(),
                        'source' => $ex->getFile() . ":" . $ex->getLine(),
                        'handler' => $matchedListener->getHandlerSlug(),
                        'project' => [
                            'name' => $project->getName(),
                            'uuid' => $project->getUuid()
                        ],
                        'event' => $event
                    ]);
                }
            }
        }
    }
}