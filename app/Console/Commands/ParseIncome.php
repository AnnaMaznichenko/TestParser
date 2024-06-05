<?php

namespace App\Console\Commands;

use App\Services\IncomeParser;
use Illuminate\Console\Command;

class ParseIncome extends Command
{
    protected $signature = 'app:parse-income {accountId}';

    protected $description = 'Parse income';

    public function __construct(private IncomeParser $incomeParser)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $this->incomeParser->parse($this->argument("accountId"));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
