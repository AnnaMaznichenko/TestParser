<?php

namespace App\Console\Commands;

use App\Services\ServiceApiCreator\ServiceApiCreator;
use Illuminate\Console\Command;

class CreateServiceApi extends Command
{
    protected $signature = 'app:create-serviceApi {host} {port}';

    protected $description = 'Create service api';

    public function __construct(private ServiceApiCreator $serviceApiCreator)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = $this->serviceApiCreator->create($this->argument("host"), $this->argument("port"));

        if (!empty($result->error)) {
            $this->error("The service api has not been created. Error: " . json_encode($result->error));
            return 1;
        }

        $this->info("The service api was created, id: {$result->id}");

        return 0;
    }
}
