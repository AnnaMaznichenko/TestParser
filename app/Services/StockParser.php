<?php

namespace App\Services;

use App\Dto\SourceApiConfig;
use App\Models\Stock;
use App\Models\Token;
use App\Providers\AppServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StockParser
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

        DB::disableQueryLog();
        $dispatcher = DB::connection()->getEventDispatcher();
        DB::connection()->unsetEventDispatcher();
        $client = new Client(['base_uri' => "http://{$this->sourceApiConfig->getHost()}:{$this->sourceApiConfig->getPort()}/api/"]);
        $limit = 500;
        $dateFrom = date("Y-m-d");
        $page = 1;
        $totalPage = 0;

        if (Stock::where("account_id", "=", $accountId)->exists()) {
            Stock::where("account_id", "=", $accountId)->where("date", "=", $dateFrom)->delete();
        }

        do {
            if ($page !== 0) {
                usleep(500000);
            }
            $response = $client->request("GET", "stocks", [
                'query' => [
                    'dateFrom' => $dateFrom,
                    'page' => $page,
                    'key' => $token->token,
                    'limit' => $limit,
                ]
            ]);
            if ($response->getStatusCode() !== 200) {
                throw new \Exception("response status is not ok");
            }
            $body = $response->getBody();
            $stocks = json_decode($body->getContents());
            if ($totalPage === 0) {
                $totalPage = ceil(($stocks->meta->total ?? 0) / $limit);
            }
            Stock::insert($this->extractStock($stocks->data ?? [], $accountId));
        } while ($page++ < $totalPage);
        DB::enableQueryLog();
        DB::connection()->setEventDispatcher($dispatcher);
    }

    public function extractStock(array $stocks, int $accountId): array
    {
        $data = [];
        foreach ($stocks as $stock) {
            $data[] = [
                "date" => $stock->date,
                "last_change_date" => $stock->last_change_date,
                "supplier_article" => $stock->supplier_article,
                "tech_size" => $stock->tech_size,
                "barcode" => $stock->barcode,
                "quantity" => $stock->quantity,
                "is_supply" => $stock->is_supply,
                "is_realization" => $stock->is_realization,
                "quantity_full" => $stock->quantity_full,
                "warehouse_name" => $stock->warehouse_name,
                "in_way_to_client" => $stock->in_way_to_client,
                "in_way_from_client" => $stock->in_way_from_client,
                "nm_id" => $stock->nm_id,
                "subject" => $stock->subject,
                "category" => $stock->category,
                "brand" => $stock->brand,
                "sc_code" => $stock->sc_code,
                "price" => $stock->price,
                "discount" => $stock->discount,
                "account_id" => $accountId,
            ];
        }

        return $data;
    }
}
