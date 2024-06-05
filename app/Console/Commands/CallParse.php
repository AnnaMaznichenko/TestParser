<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CallParse extends Command
{
    protected $signature = 'app:call-parse';

    protected $description = 'Call parse';

    public function handle(): int
    {
        Log::info("Start parsing for all accounts");
        foreach (Account::all(['id']) as $account) {
            Log::info("Start parsing for account: {$account->id}");
            if ($this->parseForAccount($account->id) !== 0) {
                $this->error("parsing error for accountId: {$account->id}");
                return 1;
            }
        }

        return 0;
    }

    private function parseForAccount(int $accountId): int
    {
        $result = $this->call('app:parse-stock', [
            'accountId' => $accountId,
        ]);
        if ($result !== 0) {
            $this->error("parsing stock error");
            return 1;
        }

        $result = $this->call('app:parse-income', [
            'accountId' => $accountId,
        ]);
        if ($result !== 0) {
            $this->error("parsing income error");
            return 1;
        }

        $result = $this->call('app:parse-order', [
            'accountId' => $accountId,
        ]);
        if ($result !== 0) {
            $this->error("parsing order error");
            return 1;
        }

        $result = $this->call('app:parse-sale', [
            'accountId' => $accountId,
        ]);
        if ($result !== 0) {
            $this->error("parsing sale error");
            return 1;
        }

        return 0;
    }
}
