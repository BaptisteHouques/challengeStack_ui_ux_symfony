<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilInfosType;
use App\Form\UserType;
use App\Repository\ActionRepository;
use App\Repository\UserActionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    /**
     * @var UserInterface
     */
    private UserInterface $user;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->user = $userRepository->find($security->getUser());
    }

    #[Route('/', name: 'app_profil_index', methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->user;
        $form = $this->createForm(ProfilInfosType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profil_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/actions', name: 'app_profil_actions', methods: ['GET'])]
    public function showAction(UserActionRepository $userActionRepository, ActionRepository $actionRepository): Response
    {
        $user = $this->user;
        $userActions = $userActionRepository->findBy(['user' => $user]);
        $actions = [];
        foreach ($userActions as $userAction) {
            $actions[] = $actionRepository->find($userAction->getAction());
        }
        return $this->render('profil/action.html.twig', [
            'actions' => $actions,
        ]);
    }
}
