<?php

namespace App\Services;

use App\Dto\SourceApiConfig;
use App\Models\Order;
use App\Models\Token;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderParser
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

        Log::info("Let's start parsing order for accountId: {$accountId}");
        DB::disableQueryLog();
        $dispatcher = DB::connection()->getEventDispatcher();
        DB::connection()->unsetEventDispatcher();
        $client = new Client(['base_uri' => "http://{$this->sourceApiConfig->getHost()}:{$this->sourceApiConfig->getPort()}/api/"]);
        $limit = 500;
        $dateTo = date("Y-m-d");
        $dateFrom = "2023-01-01";

        if (Order::where("account_id", "=", $accountId)->exists()) {
            $dateFrom = $dateTo;
            Log::info("Deleting order for the current date");
            Order::where("account_id", "=", $accountId)->where("date", ">", "{$dateFrom} 00:00:00")->delete();
            Log::info("Parsing order for the current date");
        } else {
            Log::info("Parsing order for the period from 2023-01-01 to the current date");
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
                    'key' => $token->token,
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
            Order::insert($this->extractOrders($orders->data ?? [], $accountId));
            Log::info("Part of the data was saved successfully");
        } while ($page++ < $totalPage);

        Log::info("Parsing order completed");
        DB::enableQueryLog();
        DB::connection()->setEventDispatcher($dispatcher);
    }

    public function extractOrders(array $orders, int $accountId): array
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
                "account_id" => $accountId,
            ];
        }

        return $data;
    }
}
