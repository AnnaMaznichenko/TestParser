<?php

namespace App\Services\TokenCreator;

use App\Models\Account;
use App\Models\ServiceApi;
use App\Models\Token;
use App\Models\TokenType;
use App\Services\TokenCreator\CreateResult;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TokenCreator
{
    public function create(string $token, int $tokenTypeId, int $serviceApiId): CreateResult
    {
        $attributes = [
            "token" => $token,
            "token_type_id" => $tokenTypeId,
            "service_api_id" => $serviceApiId,
        ];
        $validator = Validator::make($attributes, [
            "token" => "required",
            "token_type_id" => "exists:" . TokenType::class . ",id",
            "service_api_id" => "exists:" . ServiceApi::class . ",id",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->errors = $validator->messages()->messages();

            return $result;
        }

        $token = Token::create($attributes);
        $result->id = $token->id;

        return $result;
    }
}
