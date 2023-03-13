<?php

namespace App\Validator;

use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    protected ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function doValidate($value, $constraints = null, $groups = null)
    {
        $errors = $this->validator->validate($value, $constraints, $groups);
        if (0 !== $errors->count()) {
            throw new ValidatorException($errors->get(0)->getMessage());
        }
    }
}