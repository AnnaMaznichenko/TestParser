<?php

namespace App\Console\Commands;

use App\Services\TokenCreator\TokenCreator;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    protected $signature = 'app:create-token {token} {tokenTypeId} {serviceApiId}';

    protected $description = 'Create token';

    public function __construct(private TokenCreator $tokenCreator)
    {
        parent::__construct();
    }
    public function handle()
    {
        $result = $this->tokenCreator->create(
            $this->argument("token"),
            $this->argument("tokenTypeId"),
            $this->argument("serviceApiId"),
        );

        if (!empty($result->errors)) {
            $this->error("The token has not been created. Error: " . json_encode($result->errors));
            return 1;
        }

        $this->info("The token was created, id: {$result->id}");

        return 0;
    }
}
