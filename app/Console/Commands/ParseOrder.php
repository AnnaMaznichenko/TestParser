<?php

namespace App\Console\Commands;

use App\Services\OrderParser;
use Illuminate\Console\Command;

class ParseOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse order';

    public function __construct(private OrderParser $orderParser)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->orderParser->parse();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
