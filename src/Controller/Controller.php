<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Controller extends AbstractController
{
    protected function setSuccessMessage(?string $message = 'message.success'): void
    {
        $this->addFlash('success', $message);
    }

    protected function setErrorMessage(?string $message = 'message.error'): void
    {
        $this->addFlash('error', $message);
    }
}