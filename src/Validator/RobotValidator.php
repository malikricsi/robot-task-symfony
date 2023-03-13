<?php

namespace App\Validator;

use App\Constraint\ExistsInDatabaseTable;
use App\Entity\Robot;
use Symfony\Component\Validator\Constraints as Assert;

class RobotValidator extends Validator
{
    public function validateIds(array $ids)
    {
        $this->doValidate($ids, [
            'ids' => new Assert\Count(null, 2, null, null, null, 'validation.at_least_two_elements'),
            new Assert\All(['constraints' => [
                new Assert\Type('integer', 'validation.id_should_be_integer'),
                new ExistsInDatabaseTable(new Robot()),
            ]])
        ]);
    }
}