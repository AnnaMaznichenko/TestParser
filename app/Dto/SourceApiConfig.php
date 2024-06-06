<?php

namespace App\Dto;

class SourceApiConfig
{
    public function __construct(private string $host, private string $port)
    {
        if (empty($host)) {
            throw new \Exception("host is empty");
        }
        if (empty($port)) {
            throw new \Exception("port is empty");
        }
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getKey(): string
    {
        return $this->key;
    }

}
