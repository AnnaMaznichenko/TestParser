<?php

namespace App\Services;

use App\Dto\SourceApiConfig;
use App\Models\Income;
use App\Models\Token;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeParser
{
    public function __construct(private SourceApiConfig $sourceApiConfig)
    {
    }

    public function parse(int $accountId): void
    {
        $token = Token::whereHas("account", function (Builder $query) use ($accountId) {
            $query->where("id", "=", $accountId);
        })->first();

        if ($token === null) {
            throw new \Exception("No token found for this account");
        }

        Log::info("Let's start parsing income for accountId: {$accountId}");
        DB::disableQueryLog();
        $dispatcher = DB::connection()->getEventDispatcher();
        DB::connection()->unsetEventDispatcher();
        $client = new Client(['base_uri' => "http://{$this->sourceApiConfig->getHost()}:{$this->sourceApiConfig->getPort()}/api/"]);
        $limit = 500;
        $dateTo = date("Y-m-d");
        $dateFrom = "2023-01-01";

        if (Income::where("account_id", "=", $accountId)->exists()) {
            $dateFrom = $dateTo;
            Log::info("Deleting income for the current date");
            Income::where("account_id", "=", $accountId)->where("date", "=", $dateFrom)->delete();
            Log::info("Parsing income for the current date");
        } else {
            Log::info("Parsing income for the period from 2023-01-01 to the current date");
        }

        $page = 1;
        $totalPage = 0;
        do {
            $response = $client->request('GET', 'incomes', [
                'query' => [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'page' => $page,
                    'key' => $token->token,
                    'limit' => $limit,
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception("response status is not ok");
            }

            $body = $response->getBody();
            $incomes = json_decode($body->getContents());

            if ($totalPage === 0) {
                $totalPage = ceil(($incomes->meta->total ?? 0) / $limit);
            }

            Income::insert($this->extractIncomes($incomes->data ?? [], $accountId));
            Log::info("Part of the data was saved successfully");
        } while ($page++ < $totalPage);

        Log::info("Parsing income completed");
        DB::enableQueryLog();
        DB::connection()->setEventDispatcher($dispatcher);
    }
    public function extractIncomes(array $incomes, int $accountId): array
    {
        $data = [];
        foreach ($incomes as $income) {
            $data[] = [
                'income_id' => $income->income_id,
                'number' => $income->number,
                'date' => $income->date,
                'last_change_date' => $income->last_change_date,
                'supplier_article' => $income->supplier_article,
                'tech_size' => $income->tech_size,
                'barcode' => $income->barcode,
                'quantity' => $income->quantity,
                'total_price' => $income->total_price,
                'date_close' => $income->date_close,
                'warehouse_name' => $income->warehouse_name,
                'nm_id' => $income->nm_id,
                'status' => $income->status,
                'account_id' => $accountId,
            ];
        }

        return $data;
    }
}
