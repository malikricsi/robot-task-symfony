<?php

namespace App\Repository\Traits;

use App\Enum\EntityEnum;

trait SoftDeletableTrait
{
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $entity = parent::find($id, $lockMode, $lockVersion);
        if (null === $entity || (EntityEnum::STATE_DELETED === $entity->getState())) {
            return null;
        }
        return $entity;
    }
}