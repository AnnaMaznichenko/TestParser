<?php

namespace App\Services;

use App\Models\Income;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class IncomeParser
{
    /**
     * @throws \Exception
     */
    public function __construct(private string $host, private string $port, private string $key)
    {
        if (empty($host)) {
            throw new \Exception("host is empty");
        }
        if (empty($port)) {
            throw new \Exception("port is empty");
        }
        if (empty($key)) {
            throw new \Exception("key is empty");
        }
    }

    public function parse()
    {
        $client = new Client(['base_uri' => "http://{$this->host}:{$this->port}/api/"]);
        $response = $client->request('GET', 'incomes', [
            'query' => [
                'dateFrom' => '1974-05-21',
                'dateTo' => '2024-05-21',
                'page' => 1,
                'key' => $this->key,
                'limit' => 2
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("response status is not ok");
        }
        $body = $response->getBody();
        $incomes = json_decode($body->getContents());
        $data = [];
        foreach ($incomes->data as $incomeData) {
            $data[] = [
                'income_id' => $incomeData->income_id,
                'number' => $incomeData->number,
                'date' => $incomeData->date,
                'last_change_date' => $incomeData->last_change_date,
                'supplier_article' => $incomeData->supplier_article,
                'tech_size' => $incomeData->tech_size,
                'barcode' => $incomeData->barcode,
                'quantity' => $incomeData->quantity,
                'total_price' => $incomeData->total_price,
                'date_close' => $incomeData->date_close,
                'warehouse_name' => $incomeData->warehouse_name,
                'nm_id' => $incomeData->nm_id,
                'status' => $incomeData->status,
            ];
        }
        Income::insert($data);
    }
}
