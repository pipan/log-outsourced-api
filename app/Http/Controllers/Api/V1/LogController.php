<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Listener\ListenerPatternMatcher;
use App\Handler\LogHandlerContainer;
use App\Repository\Repository;
use Illuminate\Http\Request;
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
            return response([], 422);
        }

        $project = $repository->project()->getByUuid($uuid);
        if ($project == null) {
            return response([], 404);
        }
        $listners = $repository->listener()->getForProject($project->getId());

        $this->proccessBatch(
            [$request->all()],
            $listners,
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
            return response([], 422);
        }

        $project = $repository->project()->getByUuid($uuid);
        if ($project == null) {
            return response([], 404);
        }
        $listners = $repository->listener()->getForProject($project->getId());

        $this->proccessBatch(
            $request->all(),
            $listners,
            $matcher,
            $handlerContainer
        );

        return response([], 200);
    }

    private function proccessBatch($events, $listners, ListenerPatternMatcher $matcher, LogHandlerContainer $handlerContainer)
    {
        foreach ($events as $event) {
            $matched = $matcher->match($event['level'], $listners);
            foreach ($matched as $matchedListener) {
                $logHandler = $handlerContainer->get($matchedListener->getHandlerSlug());
                if ($logHandler == null) {
                    // TODO: non existing handler
                    continue;
                }
                $logHandler->handle(
                    $event,
                    $matchedListener->getHandlerValues()
                );
            }
        }
    }
}