<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Admin;
use App\Entity\Categories;
use App\Entity\Favoriser;
use App\Entity\Biens;
use App\Repository\BiensRepository;
use App\Form\DeleteAdminType;
use App\Form\DeleteFavorisType;
use App\Form\DeleteBiensType;
use App\Form\SelectAdminType;
use App\Form\EditAdminType;
use App\Form\EditCategoriesType;
use App\Form\EditBiensType;
use App\Form\ListBiensType;
use App\Form\AddCategoriesType;
use App\Form\AddAdminType;
use App\Form\AddBiensType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/gestionBiens/Ajout', name: 'app_biens_new')]
    public function new(Request $request, BiensRepository $biensRepository): Response
    {
        $bien = new Biens();
        $form = $this->createForm(AddBiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bien = $form->getData();
            $bien->setCategorie($form->get('categories')->getData());
            $biensRepository->save($bien, true);
            $this->addFlash('success', 'Le bien "'.$bien->getIntitule().'"a été ajouté');
            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/addBien.html.twig', [
            'addBienForm' => $form,
            'title' => "Ajouter un Bien"
        ]);
    }

    #[Route('/gestionBiens/edit', name: 'app_biens_edit')]
    public function edit(Request $request, BiensRepository $biensRepository): Response
    {
        $bien = new Biens();
        $formlist = $this->createForm(listBiensType::class, $bien);
        $form = $this->createForm(EditBiensType::class, $bien);
        $formlist->handleRequest($request);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $form->get('modifier')->isClicked())
        {
            $bienmodif = $form->getData();
            $bien=$biensRepository->find($form->get('id')->getData());

            $bien->setPrix($bienmodif->getPrix());
            $bien->setSurface($bienmodif->getSurface());
            $bien->setType($bienmodif->getType());
            $bien->setLocalisation($bienmodif->getLocalisation());
            $bien->setIntitule($bienmodif->getIntitule());
            $bien->setDescriptif($bienmodif->getDescriptif());
            $bien->setReference($bienmodif->getReference());
            $bien->setCategorie($form->get('categories')->getData());

            $biensRepository->save($bien, true);
            $this->addFlash('success', 'Le bien "'.$bien->getIntitule().'"a été modifié');
            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }
        
        if ($formlist->isSubmitted() && $formlist->isValid()) {
            $bien= $formlist->get('biens')->getData();
            $form = $this->createForm(EditBiensType::class, $bien);
            $form->get('id')->setData($bien->getId());
        }

        return $this->renderForm('admin/editBien.html.twig', [
            'listBienform' => $formlist,
            'editBienform' => $form,
            'title' => "Modifier un Bien"
        ]);
    }

    #[Route('/gestionBiens/delete', name: 'app_biens_delete')]
    public function delete(Request $request, ManagerRegistry $doctrine, BiensRepository $biensRepository): Response
    {
        $bien = new Biens();
        $form = $this->createForm(DeleteBiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bien = $form->get('biens')->getData();
            $bien = $doctrine->getRepository(Biens::class)->find($bien->getId());
            $biensRepository->remove($bien, true);
            $this->addFlash('success', 'Le bien "'.$bien->getIntitule().'"a été supprimé');
            $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/deleteBien.html.twig',[
            'deleteBienForm' => $form,
            'title' => "Supprimer un Bien"
        ]);
    }
}
