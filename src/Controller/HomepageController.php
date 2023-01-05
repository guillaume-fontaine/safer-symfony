<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Biens;
use App\Entity\Categories;
use App\Form\SearchInBiensbyCategorieType;
use App\Repository\BiensRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HomepageController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $biens = $doctrine->getRepository(Biens::class)->threeRandomGoods();
        
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'biens' => $biens,
        ]);
    }

    #[Route('/categorie', name: 'app_Categorie')]
    public function categorie(ManagerRegistry $doctrine, Request $request): Response
    {
        //<<PARTIE AFFICHAGE DES BIENS>>
        $request = Request::createFromGlobals();
        $request->getPathInfo();// the URI being requested (e.g. /about) minus any query parameters //cp colle de la doc
        $id = $request->query->get('id'); // transforme le type de requete vers "int"
        if($id==null){ 
            //Ne devrait pas arriver car les liens de la topbar ne sont creer que si il existe des categories, mais dans le doute
            throw $this->createNotFoundException('Aucune categorie existante');
        }
        
        

        //<<PARTIE FORMULAIRE MULTICRITERE>>
        //par defaut chaque champ du formulaire aurait ete requis
        //au final j'utilise que la localistion donc autant y aller abruptment
        //Comme le form n'est pas mappe, il est ici pour le moment. Si tu veux le deplacer dans un FormType why not
        //, c'est juste que la doc ici le placait dans le controller : https://symfony.com/doc/current/form/without_class.html
        $form = $this->createFormBuilder() 
        ->add('prix_min', TextType::class, ['required' => false])//aurait ete non mappe
        ->add('prix_max', TextType::class, ['required' => false])//aurait ete non mappe
        ->add('localisation', TextType::class, ['required' => false])//aurait ete mappe
        ->add('mot_clef', TextType::class) //aurait ete non mappe, normalement c'est motclefS mais il faudrait gerer le querybuilder avec les where
        ->add('search', SubmitType::class)
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $formdata = $form->getData(); //Renvoie un tableau ! A savoir que avec un objet le get data n'aurait pas renvoye les morceau du form non mappe et aurait du etre recuperer individuellement
            $biens = $doctrine->getRepository(Biens::class)->goodsfromIdandForm($id,$formdata);
        }
        else {
            $biens = $doctrine->getRepository(Biens::class)->allGoodsfromCategorie($id);
        }

        //s'il n'y a pas de biens, alors il s'agit d'un moyen de recuperer le nom de la categorie
        $categorie = $doctrine->getRepository(Categories::class)->find($id);

        return $this->render('homepage/categorie.html.twig', [
            'biens' => $biens,
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }
}
