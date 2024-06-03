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
            "name" => "required|unique:" . Company::class . ",name",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->errors = $validator->messages()->messages();

            return $result;
        }

        $company = Company::create($attributes);
        $result->id = $company->id;

        return $result;
    }
}
