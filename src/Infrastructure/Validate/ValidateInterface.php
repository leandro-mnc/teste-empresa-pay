<?php

namespace App\Infrastructure\Validate;

interface ValidateInterface
{
    public function validate(array $data);

    public function getErrors();
}
