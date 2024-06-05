<?php

namespace App\Console\Commands;

use App\Services\OrderParser;
use Illuminate\Console\Command;

class ParseOrder extends Command
{
    protected $signature = 'app:parse-order {accountId}';

    protected $description = 'Parse order';

    public function __construct(private OrderParser $orderParser)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $this->orderParser->parse($this->argument("accountId"));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
