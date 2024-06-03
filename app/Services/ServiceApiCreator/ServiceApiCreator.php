<?php

namespace App\Services\ServiceApiCreator;

use App\Models\ServiceApi;
use Illuminate\Support\Facades\Validator;

class ServiceApiCreator
{
    public function create(string $host, int $port): CreateResult
    {
        $attributes = [
            "host" => $host,
            "port" => $port,
        ];
        $validator = Validator::make($attributes, [
            "host" => "required",
            "port" => "numeric|min:0|max:65535",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->error = $validator->messages()->messages();

            return $result;
        }

        $serviceApi = ServiceApi::create($attributes);
        $result->id = $serviceApi->id;

        return $result;
    }
}
