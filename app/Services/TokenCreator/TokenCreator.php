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
    public function create(string $token, int $tokenTypeId, int $accountId, int $serviceApiId): CreateResult
    {
        $attributes = [
            "token" => $token,
            "token_type_id" => $tokenTypeId,
            "account_id" => $accountId,
            "service_api_id" => $serviceApiId,
        ];
        $validator = Validator::make($attributes, [
            "token" => "required",
            "token_type_id" => [
                "exists:" . TokenType::class . ",id",
                Rule::unique(Token::class)->where(function ($query) use($tokenTypeId, $accountId, $serviceApiId) {
                    return $query->where('token_type_id', $tokenTypeId)
                        ->where('account_id', $accountId)
                        ->where('service_api_id', $serviceApiId);
                }),
            ],
            "account_id" => "exists:" . Account::class. ",id",
            "service_api_id" => "exists:" . ServiceApi::class . ",id",
        ], [
            "token_type_id.unique" => "only one token is allowed per type per service api and account",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->errors = $validator->messages()->messages();

            return $result;
        }

        Token::create($attributes);

        return $result;
    }
}
