<?php

namespace App\Constraint;

use App\Entity\Entity;

class ExistsInDatabaseTable extends Constraint
{
    public string $message = 'validation.entity_does_not_exist_with_id';
    public string $property = 'id';
    public Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        parent::__construct();
    }

    public function validatedBy()
    {
        return 'exists_in_database_validator';
    }
}