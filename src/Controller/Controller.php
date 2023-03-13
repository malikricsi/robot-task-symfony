<?php

namespace App\Controller;

use App\Entity\Entity;
use App\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Exception\ValidatorException;

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

    protected function validateEntity(Entity $entity, Validator $validator, string $redirectRouteName): ?RedirectResponse
    {
        try {
            $validator->doValidate($entity);
            return null;
        } catch (ValidatorException $e) {
            $this->setErrorMessage($e->getMessage());
            return $this->redirectToRoute($redirectRouteName);
        }
    }
}