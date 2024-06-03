<?php

namespace App\Services\AccountCreator;

use App\Models\Account;
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
            "name" => "required|unique:App\Models\Company,name",
            "company_id" => "exists:App\Models\Company,id",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->error = $validator->messages()->messages();

            return $result;
        }

        $account = Account::create($attributes);
        $result->id = $account->id;

        return $result;
    }
}
