<?php

namespace App\Services\AccountCreator;

use App\Models\Account;
use App\Models\Company;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class AccountCreator
{
    public function create(string $name, int $companyId, int $tokenId): CreateResult
    {
        $attributes = [
            "name" => $name,
            "company_id" => $companyId,
            "token_id" => $tokenId,
        ];
        $validator = Validator::make($attributes, [
            "name" => "required|unique:" . Account::class . ",name",
            "company_id" => "exists:" . Company::class . ",id",
            "token_id" => "exists:" . Token::class . ",id",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->errors = $validator->messages()->messages();

            return $result;
        }

        $account = Account::create($attributes);
        $result->id = $account->id;

        return $result;
    }
}
