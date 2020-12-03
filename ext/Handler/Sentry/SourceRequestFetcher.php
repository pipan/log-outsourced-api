<?php

namespace Ext\Handler\Sentry;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Sentry\Integration\RequestFetcherInterface;

class SourceRequestFetcher implements RequestFetcherInterface
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function fetchRequest(): ?ServerRequestInterface
    {
        return new ServerRequest(
            $this->request['method'] ?? '',
            new Uri($this->request['url'] ?? '')
        );
    }
}