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
    private $repository;
    private $handlerContainer;
    private $matcher;

    public function __construct(Repository $repository, LogHandlerContainer $handlerContainer, ListenerPatternMatcher $matcher)
    {
        $this->repository = $repository;
        $this->handlerContainer = $handlerContainer;
        $this->matcher = $matcher;
    }

    public function singleWithKey($key, Request $request)
    {
        $projectKey = $this->repository->projectKey()->getByKey($key);
        if (!$projectKey) {
            return ResponseError::resourceNotFound();
        }

        $project = $this->repository->project()->get($projectKey->getProjectId());
        if ($project == null) {
            return ResponseError::resourceNotFound();
        }
        
        return $this->singleWithProject($project, $request);
    }

    public function singleApi(Request $request)
    {
        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid')
        );
        if ($project == null) {
            return ResponseError::resourceNotFound();
        }

        return $this->singleWithProject($project, $request);
    }

    protected function singleWithProject(ProjectEntity $project, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => ['required', Rule::in([LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE, LogLevel::WARNING, LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY])],
            'message' => ['required']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $listners = $this->repository->listener()->getAllForProject($project->getId());

        $this->proccessBatch(
            [$request->all()],
            $listners,
            $project
        );

        return response([], 200);
    }

    public function batchWithKey($key, Request $request)
    {
        $projectKey = $this->repository->projectKey()->getByKey($key);
        if (!$projectKey) {
            return ResponseError::resourceNotFound();
        }
        $project = $this->repository->project()->get($projectKey->getProjectId());
        if ($project == null) {
            return ResponseError::resourceNotFound();
        }

        return $this->batchWithProject($project, $request);
    }

    public function batchApi(Request $request)
    {
        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid')
        );
        if ($project == null) {
            return ResponseError::resourceNotFound();
        }

        return $this->batchWithProject($project, $request);
    }

    protected function batchWithProject(ProjectEntity $project, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logs' => ['required', 'array'],
            'logs.*.level' => ['required', Rule::in([LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE, LogLevel::WARNING, LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY])],
            'logs.*.message' => ['required']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $listners = $this->repository->listener()->getAllForProject($project->getId());

        $this->proccessBatch(
            $request->input('logs', []),
            $listners,
            $project
        );

        return response([], 200);
    }

    private function proccessBatch($events, $listners, ProjectEntity $project)
    {
        foreach ($events as $event) {
            $matched = $this->matcher->match($event['level'], $listners);
            foreach ($matched as $matchedListener) {
                $logHandler = $this->handlerContainer->get($matchedListener->getHandlerSlug());
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