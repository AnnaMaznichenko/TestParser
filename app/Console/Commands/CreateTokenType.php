<?php

namespace App\Console\Commands;

use App\Services\TokenTypeCreator\TokenTypeCreator;
use Illuminate\Console\Command;

class CreateTokenType extends Command
{
    protected $signature = 'app:create-tokenType {name}';

    protected $description = 'Create token type';

    public function __construct(private TokenTypeCreator $tokenTypeCreator)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = $this->tokenTypeCreator->create($this->argument("name"));

        if (!empty($result->error)) {
            $this->error("The token type has not been created. Error: " . json_encode($result->error));
            return 1;
        }

        $this->info("The token type was created, id: {$result->id}");

        return 0;
    }
}
