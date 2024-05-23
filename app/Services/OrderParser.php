<?php

namespace App\Services;

use App\Dto\SourceApiConfig;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class OrderParser
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
        $year = date("Y");
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
                $response = $client->request('GET', 'orders', [
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
                $orders = json_decode($body->getContents());
                if ($totalPage === 0) {
                    $totalPage = ceil(($orders->meta->total ?? 0) / $limit);
                }
                Order::insert($this->extractOrders($orders->data ?? []));
            } while ($page++ < $totalPage);
        }
        DB::enableQueryLog();
        DB::connection()->setEventDispatcher($dispatcher);
    }

    public function extractOrders(array $orders): array
    {
        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                "g_number" => $order->g_number,
                "date" => $order->date,
                "last_change_date" => $order->last_change_date,
                "supplier_article" => $order->supplier_article,
                "tech_size" => $order->tech_size,
                "barcode" => $order->barcode,
                "total_price" => $order->total_price,
                "discount_percent" => $order->discount_percent,
                "warehouse_name" => $order->warehouse_name,
                "oblast" => $order->oblast,
                "income_id" => $order->income_id,
                "odid" => $order->odid,
                "nm_id" => $order->nm_id,
                "subject" => $order->subject,
                "category" => $order->category,
                "brand" => $order->brand,
                "is_cancel" => $order->is_cancel,
                "cancel_dt" => $order->cancel_dt,
            ];
        }

        return $data;
    }
}
