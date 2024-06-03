<?php

namespace App\Services\CompanyCreator;

use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class CompanyCreator
{
    public function create(string $name): CreateResult
    {
        $attributes = ["name" => $name];
        $validator = Validator::make($attributes, [
            "name" => "required|unique:App\Models\Company,name",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->error = $validator->messages()->messages();

            return $result;
        }

        $company = Company::create($attributes);
        $result->id = $company->id;

        return $result;
    }
}
