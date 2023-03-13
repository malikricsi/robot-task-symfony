<?php

namespace App\Validator;

use App\Constraint\ExistsInDatabaseTable;
use App\DTO\Combat;
use App\Entity\Robot;
use Symfony\Component\Validator\Constraints as Assert;

class CombatValidator extends Validator
{
    public function validate(Combat $combat)
    {
        $this->doValidate($combat->getIds(), [
            'ids' => new Assert\Count(null, 2, null, null, null, 'validation.at_least_two_elements'),
            new Assert\All(['constraints' => [
                new Assert\Type('integer', 'validation.id_should_be_integer'),
                new ExistsInDatabaseTable(new Robot()),
            ]])
        ]);
    }
}