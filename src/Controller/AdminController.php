<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Admin;
use App\Form\DeleteAdminType;
use App\Form\SelectionModificationAdminType;
use App\Form\ModificationAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/panel', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/gestionAdmin/suppression', name: 'app_supprimer_admin')]
    public function suppressionAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeleteAdminType::class, new Admin());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->get('admin')->getData();
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/deleteAdmin.html.twig', [
            'deleteAdminForm' => $form->createView(),
        ]);
    }


    #[Route('/admin/gestionAdmin/modification', name: 'app_selectioner_modifier_admin')]
    public function selectionModificationAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SelectionModificationAdminType::class, new Admin());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->get('admin')->getData();

            return $this->redirectToRoute('app_modifier_admin', ['id' => $user->getId()]);;
        }

        return $this->render('admin/selectEditAdmin.html.twig', [
            'selectEditAdminForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/gestionAdmin/modification/{id}', name: 'app_modifier_admin')]
    public function modificationAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, Admin $user): Response
    {


        $form = $this->createForm(ModificationAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPasswords(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/editAdmin.html.twig', [
            'editAdminForm' => $form->createView(),
        ]);
    }
}
