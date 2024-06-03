<?php

namespace App\Console\Commands;

use App\Services\AccountCreator\AccountCreator;
use Illuminate\Console\Command;

class CreateAccount extends Command
{
    protected $signature = 'app:create-account {name} {company_id}';

    protected $description = 'Create account';

    public function __construct(private AccountCreator $accountCreator)
    {
        parent::__construct();
    }

    public function handle()
    {
        $result = $this->accountCreator->create($this->argument("name"), $this->argument("company_id"));

        if (!empty($result->errors)) {
            $this->error("The account has not been created. Error: " . json_encode($result->errors));
            return 1;
        }

        $this->info("The account was created, id: {$result->id}");

        return 0;
    }
}
