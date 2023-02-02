<?php

namespace App\Controller;

use App\Entity\Action;
use App\Form\ActionType;
use App\Repository\ActionRepository;
use App\Repository\UserActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/action')]
class ActionController extends AbstractController
{
    #[Route('/', name: 'app_action_index', methods: ['GET'])]
    public function index(ActionRepository $actionRepository): Response
    {
        return $this->render('action/index.html.twig', [
            'actions' => $actionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_action_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActionRepository $actionRepository, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ACTION_ADMIN');

        $action = new Action();
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imageFichier */
            $imageFichier = $form->get('image')->getData();

            // this condition is needed because the 'logo' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFichier) {
                $originalFilename = pathinfo($imageFichier->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFichier->guessExtension();

                // Move the file to the directory where logos are stored
                try {
                    $imageFichier->move(
                        $this->getParameter('imageAction_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'logoFilename' property to store the PDF file name
                // instead of its contents
                $action->setImage($newFilename);
            }
            $actionRepository->save($action, true);

            return $this->redirectToRoute('app_action_show', ['id' => $action->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('action/new.html.twig', [
            'action' => $action,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_action_show', methods: ['GET'])]
    public function show(Action $action, Security $security, UserActionRepository $userActionRepository): Response
    {
        $userAction = $userActionRepository->findOneBy(['user' => $security->getUser(), 'action' => $action]);
        $userAction ? $userParticipe = 1 : $userParticipe = 0;
        return $this->render('action/show.html.twig', [
            'action' => $action,
            'userParticipe' => $userParticipe
        ]);
    }

    #[Route('/{id}/edit', name: 'app_action_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Action $action, ActionRepository $actionRepository, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ACTION_ADMIN');

        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imageFichier */
            $imageFichier = $form->get('image')->getData();

            // this condition is needed because the 'logo' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFichier) {
                $originalFilename = pathinfo($imageFichier->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFichier->guessExtension();

                // Move the file to the directory where logos are stored
                try {
                    $imageFichier->move(
                        $this->getParameter('imageAction_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'logoFilename' property to store the PDF file name
                // instead of its contents
                $action->setImage($newFilename);
            }

            $actionRepository->save($action, true);

            return $this->redirectToRoute('app_action_show', ['id' => $action->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('action/edit.html.twig', [
            'action' => $action,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_action_delete', methods: ['POST'])]
    public function delete(Request $request, Action $action, ActionRepository $actionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ACTION_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$action->getId(), $request->request->get('_token'))) {
            $actionRepository->remove($action, true);
        }

        return $this->redirectToRoute('app_action_index', [], Response::HTTP_SEE_OTHER);
    }
}
