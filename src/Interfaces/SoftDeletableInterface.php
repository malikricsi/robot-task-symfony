<?php

namespace App\Interfaces;

interface SoftDeletableInterface
{
    public function getState();
    public function setState(string $state);
}