<?php

namespace App\Controller;

use App\DTO\Combat;
use App\Entity\Robot;
use App\Enum\EntityEnum;
use App\Form\RobotType;
use App\Repository\RobotRepository;
use App\Transformer\CombatTransformer;
use App\Validator\CombatValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Contracts\Translation\TranslatorInterface;

class RobotController extends Controller
{
    public function index(RobotRepository $repository): Response
    {
        return $this->render('robot/index.html.twig', [
            'entities' => $repository->findAllActive()
        ]);
    }

    public function new(Request $request, RobotRepository $repository): Response
    {
        $form = $this->createForm(RobotType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Robot $robot */
            $robot = $form->getData();
            $repository->add($robot, true);

            $this->setSuccessMessage('message.entity_saved_successfully');
            return $this->redirectToRoute('robot_index');
        }

        return $this->renderForm('robot/new.html.twig', [
            'form' => $form,
            'title' => 'form.robot.new'
        ]);
    }

    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        Robot $robot = null
    ): Response {
        if (null === $robot) {
            $this->setErrorMessage($translator->trans('validation.entity_does_not_exist_with_id', [':id' => $request->get('id')]));
        } else {
            $form = $this->createForm(RobotType::class, $robot);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                $this->setSuccessMessage('message.entity_saved_successfully');
            } else {
                return $this->renderForm('robot/new.html.twig', [
                    'form' => $form,
                    'title' => 'form.robot.edit'
                ]);
            }
        }

        return $this->redirectToRoute('robot_index');
    }

    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        Robot $robot = null
    ): Response {
        if (null === $robot) {
            $this->setErrorMessage($translator->trans('validation.entity_does_not_exist_with_id', [':id' => $request->get('id')]));
        } else {
            $robot->setState(EntityEnum::STATE_DELETED);
            $entityManager->flush();
            $this->setSuccessMessage('message.entity_deleted');
        }

        return $this->redirectToRoute('robot_index');
    }

    public function combat(
        Request           $request,
        RobotRepository   $repository,
        CombatValidator   $validator,
        CombatTransformer $transformer
    ): Response {
        $combat = new Combat($transformer->transform($request->get('id') ?? []));

        try {
            $validator->validate($combat);
        } catch (ValidatorException $e) {
            $this->setErrorMessage($e->getMessage());
            return $this->redirectToRoute('robot_index');
        }

        $entity = $repository->findWinner($combat->getIds());

        return $this->render('robot/combat.html.twig', compact('entity'));
    }

    public function apiCombat(
        Request             $request,
        RobotRepository     $repository,
        CombatValidator     $validator,
        CombatTransformer   $transformer,
        TranslatorInterface $translator
    ): Response {
        $combat = new Combat($transformer->transform($request->get('id') ?? []));

        try {
            $validator->validate($combat);
        } catch (ValidatorException $e) {
            return new JsonResponse(['error' => $translator->trans($e->getMessage())]);
        }

        $entity = $repository->findWinner($combat->getIds());

        return new JsonResponse($entity->toArray());
    }
}
