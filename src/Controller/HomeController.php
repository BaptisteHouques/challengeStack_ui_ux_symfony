<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use App\Repository\UserActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ActionRepository $actionRepository, UserActionRepository $userActionRepository, Security $security): Response
    {
        $actions = $actionRepository->createQueryBuilder('a')
        ->where('a.date > CURRENT_DATE()')
        ->orderBy('a.date', 'ASC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();

        foreach ($actions as $action) {
            $userAction = $userActionRepository->findOneBy(['action' => $action, 'user' => $security->getUser()]);
            $action->status = $userAction ? $userAction->getStatus() : 3;
            $nbInscrit = count($userActionRepository->findBy(['action' => $action, 'status' => 1]));
            $action->nbInscrit = $nbInscrit;
        }


        return $this->render('home/index.html.twig', [
            'events' => $actions,
        ]);
    }

    #[Route('/about-us', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/a_propos.html.twig');
    }
}
