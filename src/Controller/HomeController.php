<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ActionRepository $actionRepository): Response
    {
        $qb = $actionRepository->createQueryBuilder('a')
        ->where('a.date > CURRENT_DATE()')
        ->orderBy('a.date', 'ASC')
        ->setMaxResults(3)
        ->getQuery();


        return $this->render('home/index.html.twig', [
            'events' => $qb->execute(),
        ]);
    }

    #[Route('/about-us', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/a_propos.html.twig');
    }
}
