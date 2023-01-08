<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Admin;
use App\Entity\Categories;
use App\Entity\Favoriser;
use App\Entity\Contact;
use App\Form\DeleteAdminType;
use App\Form\DeleteFavorisType;
use App\Form\SelectAdminType;
use App\Form\EditAdminType;
use App\Form\EditCategoriesType;
use App\Form\AddCategoriesType;
use App\Form\AddAdminType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/panneau', name: 'app_admin')]
    public function index(ManagerRegistry $doctrine): Response
    {
        
        $contact = $doctrine->getRepository(Contact::class)->getNumberOfContactThisDay();
        $favoris = $doctrine->getRepository(Favoris::class)->getNumberOfFavorisThisDay();
        $top = $doctrine->getRepository(Favoriser::class)->getBiensFavoriserThisDay();        

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'top' => $top,
            'favoris' => $favoris,
            'contact' => $contact,
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
            'title' => "Supprimer un admin"
        ]);
    }


    #[Route('/admin/gestionAdmin/modification', name: 'app_selectioner_modifier_admin')]
    public function selectionModificationAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SelectAdminType::class, new Admin());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->get('admin')->getData();

            return $this->redirectToRoute('app_modifier_admin', ['id' => $user->getId()]);
        }

        return $this->render('admin/selectEditAdmin.html.twig', [
            'selectEditAdminForm' => $form->createView(),
            'title' => "Modifier un admin"
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
            'title' => "Sélectionner un admin à modifié"
        ]);
    }
    
    #[Route('/admin/gestionCategories/modification', name: 'app_modifier_categories')]
    public function modificationCategories(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditCategoriesType::class, new Categories());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categories = $entityManager->getRepository(Categories::class)->find($form->get('categories')->getData()->getId());

            $oldLibelle = $categories->getLibelle();
            $newLibelle = $form->get('editLibelle')->getData();

            $categories->setLibelle($newLibelle);

            $entityManager->flush();
            
            $this->addFlash('success', 'La catégorie '.$oldLibelle.' est devenue '.$newLibelle);


            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/editCategories.html.twig', [
            'editCategoriesForm' => $form->createView(),
            'title' => "Modifier une catégorie"
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
            'title' => "Ajouter une catégorie"
        ]);
    }

    #[Route('/admin/gestionAdmin/ajout', name: 'app_add_admin')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Admin();
        $form = $this->createForm(AddAdminType::class, $user);
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
            'title' => "Ajouter un admin"
        ]);
    }

    #[Route('/admin/gestionFavoris/suppression', name: 'app_delete_favoris')]
    public function suppressionFavoris(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeleteFavorisType::class, new Favoris());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $favoris = $form->get('favoris')->getData();
            foreach ($doctrine->getRepository(Favoriser::class)->getFavoriserByFavoris($favoris->getId()) as $bienFavoris) {
                $entityManager->remove($bienFavoris);
            }
            $entityManager->remove($favoris);
            $entityManager->flush();

            $this->addFlash('success', 'Le favoris a été supprimé.');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/deleteFavoris.html.twig', [
            'deleteFavorisForm' => $form->createView(),
            'title' => "Supprimer un favoris"
        ]);
    }

}
