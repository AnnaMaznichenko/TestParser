<?php

namespace App\Dto;

class SourceApiConfig
{
    public function __construct(private string $host, private string $port, private string $key)
    {
        if (empty($host)) {
            throw new \Exception("host is empty");
        }
        if (empty($port)) {
            throw new \Exception("port is empty");
        }
        if (empty($key)) {
            throw new \Exception("key is empty");
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
