<?php

namespace App\Services\AccountCreator;

use App\Models\Account;
use App\Models\Company;
use App\Services\AccountCreator\CreateResult;
use Illuminate\Support\Facades\Validator;

class AccountCreator
{
    public function create(string $name, int $company_id): CreateResult
    {
        $attributes = [
            "name" => $name,
            "company_id" => $company_id,
        ];
        $validator = Validator::make($attributes, [
            "name" => "required|unique:" . Account::class . ",name",
            "company_id" => "exists:" . Company::class . ",id",
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
