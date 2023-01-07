<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Biens;
use App\Form\FavoriserType;
use App\Entity\Categories;
use App\Form\SearchInBiensbyCategorieType;
use App\Repository\BiensRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class HomepageController extends AbstractController
{

    protected $session;
    protected $favoris;

    public function __construct()
    {

    }

    private function setupFavoris(?Request $request)
    {
        if (is_null($request)) {
            $this->session = new Session();
            $this->session->start();
        } else {
            $this->session = $request->getSession();
        }
        $this->favoris = $this->session->get('favoris');
        if (is_null($this->favoris)) {
            $this->favoris = [];
            $this->session->set('favoris', $this->favoris);
        }
    }

    private function finishFavoris()
    {
        $this->session->set('favoris', $this->favoris);
    }

    #[Route('/', name: 'app_homepage')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $biens = $doctrine->getRepository(Biens::class)->threeRandomGoods();

        return $this->render('homepage/index.html.twig', [
            'biens' => $biens,
        ]);
    }

    #[Route('/voirunbien/{id}', name: 'app_view_bien')]
    public function voirUnBien(Request $request, Biens $bien): Response
    {
        $this->setupFavoris($request);
        $isInFavoris = $this->foundBienInFavoris($bien);
        $form = $this->createForm(FavoriserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($isInFavoris) {
                $this->removeBienInFavoris($bien);
                $isInFavoris = false;
                $this->addFlash('success', 'Le bien ' . $bien->getIntitule() . ' a été retiré de la liste de favoris.');
            } else {
                array_push($this->favoris, $bien);
                $isInFavoris = true;
                $this->addFlash('success', 'Le bien ' . $bien->getIntitule() . ' a été ajouté à la liste de favoris.');
            }


        }
        $this->finishFavoris();

        return $this->render('homepage/viewBien.html.twig', [
            'bien' => $bien,
            'favoris' => $isInFavoris,
            'formFavoriser' => $form->createView(),
        ]);
    }

    private function foundBienInFavoris(Biens $biens)
    {
        foreach ($this->favoris as $bienFavoris) {
            if ($biens->getId() == $bienFavoris->getId()) {
                return true;
            }
        }
        return false;
    }


    private function removeBienInFavoris(Biens $biens)
    {
        foreach ($this->favoris as $bienFavoris) {
            if ($biens->getId() === $bienFavoris->getId()) {
                unset($this->favoris[key($this->favoris)]);
            }
        }
        return false;
    }

    
    #[Route('/voirfavoris', name: 'app_view_favoris')]
    public function voirLesFavoris(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->setupFavoris($request);

        return $this->render('homepage/viewFavoris.html.twig', [
            'biens' => $this->favoris,
        ]);
    }

    
    #[Route('/removefromfavoris/{id}', name: 'app_remove_from_favoris')]
    public function enleverDesFavoris(Biens $bien): Response
    {
        $this->setupFavoris(null);
        $this->removeBienInFavoris($bien);
        $this->finishFavoris();
        $this->addFlash('success', 'Le bien ' . $bien->getIntitule() . ' a été retiré de la liste de favoris.');

        return $this->redirectToRoute('app_view_favoris');
    }

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
        ->add('prix_min', NumberType::class, ['required' => false])//aurait ete non mappe
        ->add('prix_max', NumberType::class, ['required' => false])//aurait ete non mappe
        ->add('localisation', TextType::class, ['required' => false])//aurait ete mappe
        ->add('mot_clefs', TextType::class, ['required' => false]) //aurait ete non mappe, normalement c'est motclefS mais il faudrait gerer le querybuilder avec les where
        ->add('RECHERCHER', SubmitType::class)
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
