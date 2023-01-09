<?php

namespace App\Controller;

use App\Entity\ActionType;
use App\Form\ActionTypeType;
use App\Repository\ActionTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/action/type')]
class ActionTypeController extends AbstractController
{
    #[Route('/', name: 'app_action_type_index', methods: ['GET'])]
    public function index(ActionTypeRepository $actionTypeRepository): Response
    {
        return $this->render('action_type/index.html.twig', [
            'action_types' => $actionTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_action_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActionTypeRepository $actionTypeRepository): Response
    {
        $actionType = new ActionType();
        $form = $this->createForm(ActionTypeType::class, $actionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actionTypeRepository->save($actionType, true);

            return $this->redirectToRoute('app_action_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('action_type/new.html.twig', [
            'action_type' => $actionType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_action_type_show', methods: ['GET'])]
    public function show(ActionType $actionType): Response
    {
        return $this->render('action_type/show.html.twig', [
            'action_type' => $actionType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_action_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActionType $actionType, ActionTypeRepository $actionTypeRepository): Response
    {
        $form = $this->createForm(ActionTypeType::class, $actionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actionTypeRepository->save($actionType, true);

            return $this->redirectToRoute('app_action_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('action_type/edit.html.twig', [
            'action_type' => $actionType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_action_type_delete', methods: ['POST'])]
    public function delete(Request $request, ActionType $actionType, ActionTypeRepository $actionTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actionType->getId(), $request->request->get('_token'))) {
            $actionTypeRepository->remove($actionType, true);
        }

        return $this->redirectToRoute('app_action_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
