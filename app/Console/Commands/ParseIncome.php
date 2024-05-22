<?php

namespace App\Console\Commands;

use App\Services\IncomeParser;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ParseIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse income';

    public function __construct(private IncomeParser $incomeParser)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->incomeParser->parse();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
