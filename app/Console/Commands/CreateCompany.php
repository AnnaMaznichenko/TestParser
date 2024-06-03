<?php

namespace App\Console\Commands;

use App\Services\CompanyCreator\CompanyCreator;
use Illuminate\Console\Command;

class CreateCompany extends Command
{
    protected $signature = 'app:create-company {name}';

    protected $description = 'Create company';

    public function __construct(private CompanyCreator $companyCreator)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = $this->companyCreator->create($this->argument("name"));
        if (!empty($result->error)) {
            $this->error('The company has not been created');
            return 1;
        }
        $this->info("The company was created, id {$result->id}");
        return 0;
    }
}
