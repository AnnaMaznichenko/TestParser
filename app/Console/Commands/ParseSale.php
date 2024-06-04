<?php

namespace App\Console\Commands;

use App\Services\SaleParser;
use Illuminate\Console\Command;

class ParseSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-sale {accountId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse sale';

    public function __construct(private SaleParser $saleParser)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->saleParser->parse($this->argument("accountId"));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
