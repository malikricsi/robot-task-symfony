<?php

namespace App\Transformer;

class CombatTransformer extends Transformer
{
    public function transform(array $entityIds): array
    {
        return array_map('intval', $entityIds);
    }
}