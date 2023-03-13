<?php

namespace App\Transformer;

class RobotTransformer extends Transformer
{
    public function transformIds(array $entityIds): array
    {
        return array_map('intval', $entityIds);
    }
}