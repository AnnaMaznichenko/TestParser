<?php

namespace App\Console\Commands;

use App\Services\ServiceApiCreator\ServiceApiCreator;
use Illuminate\Console\Command;

class CreateServiceApi extends Command
{
    protected $signature = 'app:create-serviceApi {name} {tokenTypeId}';

    protected $description = 'Create service api';

    public function __construct(private ServiceApiCreator $serviceApiCreator)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = $this->serviceApiCreator->create($this->argument("name"), $this->argument("tokenTypeId"));

        if (!empty($result->errors)) {
            $this->error("The service api has not been created. Error: " . json_encode($result->errors));
            return 1;
        }

        $this->info("The service api was created, id: {$result->id}");

        return 0;
    }
}
