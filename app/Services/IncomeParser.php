<?php

namespace App\Services;

use App\Dto\SourceApiConfig;
use App\Models\Income;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class IncomeParser
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
            if ($i !== 0)  {
                $dateTo = ($year - $i + 1) . "-01-01";
                $dateFrom = ($year - $i) . "-01-01";
            }
            $page = 1;
            $totalPage = 0;
            do {
                $response = $client->request('GET', 'incomes', [
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
                $incomes = json_decode($body->getContents());
                if ($totalPage === 0) {
                    $totalPage = ceil(($incomes->meta->total ?? 0) / $limit);
                }
                Income::insert($this->extractIncomes($incomes->data ?? []));
            } while ($page++ < $totalPage);
        }
        DB::enableQueryLog();
        DB::connection()->setEventDispatcher($dispatcher);
    }
    public function extractIncomes(array $incomes): array
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
            ];
        }

        return $data;
    }
}
