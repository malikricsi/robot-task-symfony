<?php

namespace App\Constraint;

use App\Enum\EntityEnum;
use App\Interfaces\SoftDeletableInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExistsInDatabaseTableValidator extends ConstraintValidator
{
    protected EntityManagerInterface $entityManager;
    protected TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        /** @var ExistsInDatabaseTable $constraint */
        $entity = $this->entityManager->getRepository($constraint->entity->getClass())->findOneBy([
            $constraint->property => $value,
        ]);

        if (null === $entity || ($entity instanceof SoftDeletableInterface && EntityEnum::STATE_DELETED === $entity->getState())) {
            $this->context->buildViolation($this->translator->trans($constraint->message, [':id' => $value]))
                ->addViolation();
        }
    }
}