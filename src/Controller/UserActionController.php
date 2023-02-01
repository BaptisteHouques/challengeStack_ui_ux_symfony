<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\UserAction;
use App\Repository\UserActionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/userAction')]
class UserActionController extends AbstractController
{
    #[Route('/user/action', name: 'app_user_action')]
    public function index(): Response
    {
        return $this->render('user_action/index.html.twig', [
            'controller_name' => 'UserActionController',
        ]);
    }

    #[Route('/participe/{id}', name: 'app_userAction_participe', methods: ['GET','POST'])]
    public function actionParticipe(Request $request, Action $action, UserActionRepository $userActionRepository, Security $security): Response
    {
        $userAction = new UserAction();
        $userAction->setAction($action);
        $userAction->setUser($security->getUser());
        $userAction->setStatus(0);
        $userAction->setIsResponsible(0);
        $userActionRepository->save($userAction, true);
        return $this->redirectToRoute('app_action_show', ['id' => $action->getId()]);
    }
}
