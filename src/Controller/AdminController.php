<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Admin;
use App\Entity\Categories;
use App\Form\DeleteAdminType;
use App\Form\SelectionModificationAdminType;
use App\Form\EditAdminType;
use App\Form\EditCategoriesType;
use App\Form\AddCategoriesType;
use App\Form\AddAdminFormType;
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

            $this->addFlash('success', 'L\'administrateur '.$user->getNom().' '.$user->getPrenom().' a été supprimé.');

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

            return $this->redirectToRoute('app_modifier_admin', ['id' => $user->getId()]);
        }

        return $this->render('admin/selectEditAdmin.html.twig', [
            'selectEditAdminForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/gestionAdmin/modification/{id}', name: 'app_modifier_admin')]
    public function modificationAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, Admin $user): Response
    {


        $form = $this->createForm(EditAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPasswords(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $entityManager->flush();

            $this->addFlash('success', 'L\'administrateur '.$user->getNom().' '.$user->getPrenom().' a été modifié.');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/editAdmin.html.twig', [
            'editAdminForm' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/gestionCategories/modification', name: 'app_modifier_categories')]
    public function modificationCategories(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditCategoriesType::class, new Categories());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categories = $entityManager->getRepository(Categories::class)->find($form->get('categories')->getData()->getId());

            if (!$categories) {
                throw $this->createNotFoundException(
                    'Aucune catégorie trouvée pour l\'id : '.$id
                );
            }

            $oldLibelle = $categories->getLibelle();
            $newLibelle = $form->get('editLibelle')->getData();

            $categories->setLibelle($newLibelle);

            $entityManager->flush();
            
            $this->addFlash('success', 'La catégorie '.$oldLibelle.' est devenue '.$newLibelle);


            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/editCategories.html.twig', [
            'editCategoriesForm' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/gestionCategories/ajout', name: 'app_add_categories')]
    public function addCategories(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = new Categories();
        $form = $this->createForm(AddCategoriesType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            
            $this->addFlash('success', 'La catégorie '.$form->get('libelle')->getData().' ajouté');


            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/addCategories.html.twig', [
            'addCategoriesForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/gestionAdmin/ajout', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Admin();
        $form = $this->createForm(AddAdminFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPasswords(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(array('ROLE_ADMIN'));

            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'administrateur '.$user->getNom().' '.$user->getPrenom().' a été inscrit.');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/addAdmin.html.twig', [
            'addAdminForm' => $form->createView(),
        ]);
    }

}
