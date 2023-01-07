<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Form\BiensType;
use App\Repository\BiensRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/biens')] //La route a été changé pour la passer en "back end" Tout ce code a été généré par bin/console make:crud Biens
class BiensController extends AbstractController
{
    #[Route('/', name: 'app_biens_index', methods: ['GET'])]
    public function index(BiensRepository $biensRepository): Response
    {
        return $this->render('biens/index.html.twig', [
            'biens' => $biensRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_biens_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BiensRepository $biensRepository): Response
    {
        $bien = new Biens();
        $form = $this->createForm(BiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $biensRepository->save($bien, true);

            return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_biens_show', methods: ['GET'])]
    public function show(Biens $bien): Response
    {
        return $this->render('biens/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_biens_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Biens $bien, BiensRepository $biensRepository): Response
    {
        $form = $this->createForm(BiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $biensRepository->save($bien, true);

            return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_biens_delete', methods: ['POST'])]
    public function delete(Request $request, Biens $bien, BiensRepository $biensRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $biensRepository->remove($bien, true);
        }

        return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
    }
}
