<?php

namespace App\Controller;

use App\Entity\Robot;
use App\Enum\EntityEnum;
use App\Form\RobotType;
use App\Repository\RobotRepository;
use App\Transformer\RobotTransformer;
use App\Validator\RobotValidator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;

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
            $robot->setState(EntityEnum::STATE_ACTIVE);
            $robot->setCreatedAt(new DateTimeImmutable());
            $repository->add($robot, true);

            $this->setSuccessMessage('message.entity_saved_successfully');
            return $this->redirectToRoute('robot_index');
        }

        return $this->renderForm('robot/new.html.twig', [
            'form' => $form,
            'title' => 'form.robot.new'
        ]);
    }

    public function edit(Robot $robot, Request $request, EntityManagerInterface $entityManager, RobotValidator $validator): Response
    {
        if (null !== $validationResponse = $this->validateEntity($robot, $validator, 'robot_index')) {
            return $validationResponse;
        }
        $form = $this->createForm(RobotType::class, $robot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->setSuccessMessage('message.entity_saved_successfully');
            return $this->redirectToRoute('robot_index');
        }

        return $this->renderForm('robot/new.html.twig', [
            'form' => $form,
            'title' => 'form.robot.edit'
        ]);
    }

    public function delete(Robot $robot, EntityManagerInterface $entityManager, RobotValidator $validator): Response
    {
        if (null !== $validationResponse = $this->validateEntity($robot, $validator, 'robot_index')) {
            return $validationResponse;
        }
        $robot->setState(EntityEnum::STATE_DELETED);
        $entityManager->flush();

        $this->setSuccessMessage('message.entity_deleted');
        return $this->redirectToRoute('robot_index');
    }

    public function combat(
        Request $request,
        RobotRepository $repository,
        RobotValidator $validator,
        RobotTransformer $transformer
    ): Response {
        $isApiRequest = true === $request->get('api');
        try {
            $validator->validateIds(
                $transformedIds = $transformer->transformIds(
                    $request->get('id') ?? []
                )
            );
        } catch (ValidatorException $e) {
            if ($isApiRequest) {
                return new JsonResponse(['error' => $e->getMessage()]);
            } else {
                $this->setErrorMessage($e->getMessage());
                return $this->redirectToRoute('robot_index');
            }
        }

        $entity = $repository->findWinner($transformedIds);

        return $isApiRequest
            ? new JsonResponse($entity->toArray())
            : $this->render('robot/combat.html.twig', compact('entity'));
    }
}
