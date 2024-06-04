<?php

namespace App\Services;

use App\Dto\SourceApiConfig;
use App\Models\Sale;
use App\Models\Token;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleParser
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

        Log::info("Let's start parsing sale for accountId: {$accountId}");
        DB::disableQueryLog();
        $dispatcher = DB::connection()->getEventDispatcher();
        DB::connection()->unsetEventDispatcher();
        $client = new Client(['base_uri' => "http://{$this->sourceApiConfig->getHost()}:{$this->sourceApiConfig->getPort()}/api/"]);
        $limit = 500;
        $dateTo = date("Y-m-d");
        $dateFrom = "2023-01-01";

        if (Sale::where("account_id", "=", $accountId)->exists()) {
            $dateFrom = $dateTo;
            Log::info("Deleting sale for the current date");
            Sale::where("account_id", "=", $accountId)->where("date", "=", $dateFrom)->delete();
            Log::info("Parsing sale for the current date");
        } else {
            Log::info("Parsing sale for the period from 2023-01-01 to the current date");
        }

        $page = 1;
        $totalPage = 0;
        do {
            if ($page !== 0) {
                usleep(500000);
            }
            $response = $client->request('GET', 'sales', [
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
            $sales = json_decode($body->getContents());
            if ($totalPage === 0) {
                $totalPage = ceil(($sales->meta->total ?? 0) / $limit);
            }
            Sale::insert($this->extractSales($sales->data ?? [], $accountId));
            Log::info("Part of the data was saved successfully");
        } while ($page++ < $totalPage);

        Log::info("Parsing sale completed");
        DB::enableQueryLog();
        DB::connection()->setEventDispatcher($dispatcher);
    }

    public function extractSales(array $sales, int $accountId): array
    {
        $data = [];
        foreach ($sales as $sale) {
            $data[] = [
                "g_number" => $sale->g_number,
                "date" => $sale->date,
                "last_change_date" => $sale->last_change_date,
                "supplier_article" => $sale->supplier_article,
                "tech_size" => $sale->tech_size,
                "barcode" => $sale->barcode,
                "total_price" => $sale->total_price,
                "discount_percent" => $sale->discount_percent,
                "is_supply" => $sale->is_supply,
                "is_realization" => $sale->is_realization,
                "promo_code_discount" => $sale->promo_code_discount,
                "warehouse_name" => $sale->warehouse_name,
                "country_name" => $sale->country_name,
                "oblast_okrug_name" => $sale->oblast_okrug_name,
                "region_name" => $sale->region_name,
                "income_id" => $sale->income_id,
                "sale_id" => $sale->sale_id,
                "odid" => $sale->odid,
                "spp" => $sale->spp,
                "for_pay" => $sale->for_pay * 100, //store in kopecks
                "finished_price" => $sale->finished_price,
                "price_with_disc" => $sale->price_with_disc,
                "nm_id" => $sale->nm_id,
                "subject" => $sale->subject,
                "category" => $sale->category,
                "brand" => $sale->brand,
                "is_storno" => $sale->is_storno,
                "account_id" => $accountId,
            ];
        }

        return $data;
    }
}
