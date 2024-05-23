<?php

namespace App\Services;

use App\Dto\SourceApiConfig;
use App\Models\Sale;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class SaleParser
{
    public function __construct(private SourceApiConfig $sourceApiConfig)
    {
    }

    public function parse(): void
    {
        DB::disableQueryLog();
        $dispatcher = DB::connection()->getEventDispatcher();
        DB::connection()->unsetEventDispatcher();
        $client = new Client(['base_uri' => "http://{$this->sourceApiConfig->getHost()}:{$this->sourceApiConfig->getPort()}/api/"]);
        $requiredYears = 10;
        $limit = 500;
        $year = (int)date("Y");
        $dateTo = date("Y-m-d");
        $dateFrom = "{$year}-01-01";
        for ($i = 0; $i < $requiredYears; $i++) {
            if ($i !== 0) {
                $dateTo = ($year - $i + 1) . "-01-01";
                $dateFrom = ($year - $i) . "-01-01";
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
                        'key' => $this->sourceApiConfig->getKey(),
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
                Sale::insert($this->extractSales($sales->data ?? []));
            } while ($page++ < $totalPage);
        }
        DB::enableQueryLog();
        DB::connection()->setEventDispatcher($dispatcher);
    }

    public function extractSales(array $sales): array
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
            ];
        }

        return $data;
    }
}
