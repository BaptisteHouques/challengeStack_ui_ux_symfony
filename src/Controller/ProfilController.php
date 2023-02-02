<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ProfilInfosType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\ActionRepository;
use App\Repository\UserActionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
        $userActions = $userActionRepository->createQueryBuilder('u')
            ->where('u.user = :user')
            ->setParameter('user', $user)
            ->andWhere('u.status != 0')
            ->getQuery()
            ->getResult();

        $actions = [];
        $actionsEnAttentes = [];
        $actionsPasses = [];
        foreach ($userActions as $userAction) {
            $action = $actionRepository->find($userAction->getAction());
            $nbInscrit = count($userActionRepository->findBy(['action' => $action, 'status' => 1]));
            $action->nbInscrit = $nbInscrit;
            if ($userAction->getStatus() === 1) {
//                dump(date('Y-m-d h:m:s'));
//                dump($action->getDate()->format('Y-m-d h:m:s'));
//                die;
                if ($action->getDate()->format('Y-m-d h:m:s') < date('Y-m-d h:m:s')) {
                    $actionsPasses[] = $action;
                } else {
                    $actions[] = $action;
                }
            } else {
                $actionsEnAttentes[] = $action;
            }
        }
        return $this->render('profil/action.html.twig', [
            'actions' => $actions,
            'actionsEnAttentes' => $actionsEnAttentes,
            'actionsPasses' => $actionsPasses
        ]);
    }

    #[Route('/password', name: 'app_profil_password')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->user;
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();

            if ($userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_profil_password');
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }

        return $this->render('profil/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
