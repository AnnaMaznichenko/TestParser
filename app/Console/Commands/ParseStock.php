<?php

namespace App\Console\Commands;

use App\Services\StockParser;
use Illuminate\Console\Command;

class ParseStock extends Command
{
    protected $signature = 'app:parse-stock {accountId}';

    protected $description = 'Parse stock';

    public function __construct(private StockParser $stockParser)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $this->stockParser->parse($this->argument("accountId"));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
