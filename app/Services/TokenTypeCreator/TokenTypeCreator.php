<?php

namespace App\Services\TokenTypeCreator;

use App\Models\TokenType;
use App\Services\TokenTypeCreator\CreateResult;
use Illuminate\Support\Facades\Validator;

class TokenTypeCreator
{
    public function create(string $name): CreateResult
    {
        $attributes = ["name" => $name];
        $validator = Validator::make($attributes, [
            "name" => "required|unique:App\Models\TokenType,name",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->error = $validator->messages()->messages();

            return $result;
        }

        $company = TokenType::create($attributes);
        $result->id = $company->id;

        return $result;
    }
}
