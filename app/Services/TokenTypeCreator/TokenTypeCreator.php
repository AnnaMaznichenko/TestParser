<?php

namespace App\Services\TokenTypeCreator;

use App\Models\TokenType;
use Illuminate\Support\Facades\Validator;

class TokenTypeCreator
{
    public function create(string $name): CreateResult
    {
        $attributes = ["name" => $name];
        $validator = Validator::make($attributes, [
            "name" => "required|unique:" . TokenType::class . ",name",
        ]);
        $result = new CreateResult();

        if ($validator->fails()) {
            $result->errors = $validator->messages()->messages();

            return $result;
        }

        $company = TokenType::create($attributes);
        $result->id = $company->id;

        return $result;
    }
}
