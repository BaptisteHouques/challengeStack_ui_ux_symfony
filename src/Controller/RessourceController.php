<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Ressource;
use App\Form\RessourceType;
use App\Repository\RessourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/ressource')]
class RessourceController extends AbstractController
{
    #[Route('/', name: 'app_ressource_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository): Response
    {
        return $this->render('ressource/index.html.twig', [
            'ressources' => $ressourceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ressource_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RessourceRepository $ressourceRepository): Response
    {
        $ressource = new Ressource();
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ressourceRepository->save($ressource, true);

            return $this->redirectToRoute('app_ressource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ressource/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ressource_show', methods: ['GET'])]
    public function show(Ressource $ressource): Response
    {
        return $this->render('ressource/show.html.twig', [
            'ressource' => $ressource,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ressource_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ressource $ressource, RessourceRepository $ressourceRepository): Response
    {
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ressourceRepository->save($ressource, true);

            return $this->redirectToRoute('app_ressource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ressource/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ressource_delete', methods: ['POST'])]
    public function delete(Request $request, Ressource $ressource, RessourceRepository $ressourceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->request->get('_token'))) {
            $ressourceRepository->remove($ressource, true);
        }

        return $this->redirectToRoute('app_ressource_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/addToEvent/{id}', name: 'app_ressource_add', methods: ['GET', 'POST'])]
    public function add(Request $request, Action $action, RessourceRepository $ressourceRepository, SluggerInterface $slugger): Response
    {
        $ressource = new Ressource();
        $ressource->isAddition = true;
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $lienFichier */
            $lienFichier = $form->get('lien')->getData();

            // this condition is needed because the 'logo' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($lienFichier) {
                $originalFilename = pathinfo($lienFichier->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$lienFichier->guessExtension();

                // Move the file to the directory where logos are stored
                try {
                    $lienFichier->move(
                        $this->getParameter('ressources_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'logoFilename' property to store the PDF file name
                // instead of its contents
                $ressource->setLien($newFilename);
            }

            $ressource->setUser($this->getUser());
            $ressource->setAction($action);
            $ressourceRepository->save($ressource, true);

            return $this->redirectToRoute('app_ressource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ressource/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }
}
