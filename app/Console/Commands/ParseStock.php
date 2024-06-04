<?php

namespace App\Console\Commands;

use App\Services\StockParser;
use Illuminate\Console\Command;

class ParseStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-stock {accountId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse stock';

    public function __construct(private StockParser $stockParser)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
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
