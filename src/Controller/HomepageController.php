<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Biens;
use App\Entity\Categories;
use App\Entity\Favoris;
use App\Entity\Favoriser;
use App\Form\AddFavorisType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
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
        $this->finishFavoris();

        return $this->render('homepage/viewBien.html.twig', [
            'bien' => $bien,
            'favoris' => $isInFavoris,
            'routeRedirection' => rawurlencode($request->getRequestUri())
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
    public function voirLesFavoris(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $this->setupFavoris($request);
        $favoris = new Favoris();
        $form = $this->createForm(AddFavorisType::class, $favoris);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $favoris->setDate(new \DateTime());
            $entityManager->persist($favoris);

            foreach ($this->favoris as $bienFavoris) {
                $favoriser = new Favoriser();
                $favoriser->setFavoris($favoris);
                $favoriser->setBiens($doctrine->getRepository(Biens::class)->find($bienFavoris->getId()));
                $entityManager->persist($favoriser);
            }
            $entityManager->flush();
            $this->favoris = [];
            $this->finishFavoris();
            
            $this->addFlash('success', 'Vos favoris ont été envoyé sur votre adresse e-mail suivante : '.$form->get('mail')->getData().'.');
        }

        return $this->render('homepage/viewFavoris.html.twig', [
            'biens' => $this->favoris,
            'routeRedirection' => rawurlencode($request->getRequestUri()),
            'addFavorisForm' => $form->createView(),
        ]);
    }

    
    #[Route('/removefromfavoris/{id}', name: 'app_remove_from_favoris')]
    public function enleverUnFavoris(Request $request, Biens $bien): Response
    {
        $this->setupFavoris(null);
        $this->removeBienInFavoris($bien);
        $this->finishFavoris();
        $this->addFlash('success', 'Le bien ' . $bien->getIntitule() . ' a été retiré de la listes de favoris.');

        return $this->redirect($request->query->get('routeRedirection'));
    }

    
    #[Route('/addfromfavoris/{id}', name: 'app_add_from_favoris')]
    public function ajouterUnFavoris(Request $request, Biens $bien): Response
    {
        $this->setupFavoris(null);
        array_push($this->favoris, $bien);
        $this->finishFavoris();
        $this->addFlash('success', 'Le bien ' . $bien->getIntitule() . ' a été ajouté de la liste des favoris.');
        
        return $this->redirect($request->query->get('routeRedirection'));
    }

    #[Route('/categorie/{id}', name: 'app_categorie')]
    public function categorie(Request $request, ManagerRegistry $doctrine, Categories $categorie): Response
    {    
        $this->setupFavoris($request);
        if($categorie==null){ 
            //Ne devrait pas arriver car les liens de la topbar ne sont creer que si il existe des categories, mais dans le doute
            throw $this->createNotFoundException('Aucune categorie existante');
        }
        
        $biens = $categorie->getBiens();

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

        return $this->render('homepage/categorie.html.twig', [
            'biens' => $biens,
            'categorie' => $categorie,
            'favoris' => $this->favoris,
            'routeRedirection' => rawurlencode($request->getRequestUri()),
            'form' => $form,
        ]);
    }
}

