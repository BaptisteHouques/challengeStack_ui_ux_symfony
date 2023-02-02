<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserActionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $users = $userRepository->findAll();
        } else {
            $users = $userRepository->createQueryBuilder('u')
                ->Select('u.id, u.firstname, u.lastname, u.email, u.is_verified')
                ->getQuery()
                ->getResult()
            ;
        }
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $user = new User();
        $user->isCurrentUserManager = $this->isGranted('ROLE_SUPER_ADMIN');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/verif', name: 'app_user_verif', methods: ['GET'])]
    public function verif(UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_MANAGER')) {
            $this->redirectToRoute('app_user_index');
        }

        $users = $userRepository->findBy(['is_verified' => 0]);

        return $this->render('user/gerer.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $user->setRoles([]);
            $user->setPassword('');
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $user->isCurrentUserManager = $this->isGranted('ROLE_SUPER_ADMIN');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->isIsVerified() && $user->getRoles() === ['ROLE_USER']) {
                $user->setRoles(["ROLE_BENEVOLE"]);
            } elseif (!$user->isIsVerified()) {
                $user->setRoles(['ROLE_USER']);
            }

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/accept', name: 'app_user_accept', methods: ['GET', 'POST'])]
    public function accept(Request $request, User $user, UserRepository $userRepository): Response
    {
        $user->setIsVerified(1);
        $user->setRoles(["ROLE_BENEVOLE"]);
        $userRepository->save($user, true);

        return $this->redirectToRoute('app_user_verif');
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
