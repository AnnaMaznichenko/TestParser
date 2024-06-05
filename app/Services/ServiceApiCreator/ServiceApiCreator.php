<?php

namespace App\Services\ServiceApiCreator;

use App\Models\ServiceApi;
use App\Models\TokenType;
use Illuminate\Support\Facades\Validator;

class ServiceApiCreator
{
    public function create(string $name, int $tokenTypeId): CreateResult
    {
        $attributes = [
            "name" => $name,
            "token_type_id" => $tokenTypeId,
        ];
        $validator = Validator::make($attributes, [
            "name" => "required|unique:" . ServiceApi::class . ",name",
            "token_type_id" => "exists:" . TokenType::class . ",id",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->errors = $validator->messages()->messages();

            return $result;
        }

        $serviceApi = ServiceApi::create($attributes);
        $result->id = $serviceApi->id;

        return $result;
    }
}
