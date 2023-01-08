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
use App\Entity\Contact;
use App\Form\AddFavorisType;
use App\Form\ContactFormType;
use App\Form\SearchBiensCriteriaType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;

class SaferController extends AbstractController
{

    protected $session;
    protected $favoris;

    public function __construct()
    {

    }


    //Fonction qui sert généré les favoris stocké en session
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

    //Stock les favoris en session
    private function finishFavoris()
    {
        $this->session->set('favoris', $this->favoris);
    }

    //Page par default
    #[Route('/', name: 'app_homepage')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $biens = $doctrine->getRepository(Biens::class)->threeRandomGoods();

        return $this->render('homepage/index.html.twig', [
            'biens' => $biens,
        ]);
    }

    //Page qui affiche un bien donné en paramètre
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

    //Fonction qui renvoie true si le bien en parametre est stocké en favoris
    private function foundBienInFavoris(Biens $biens)
    {
        foreach ($this->favoris as $bienFavoris) {
            if ($biens->getId() == $bienFavoris->getId()) {
                return true;
            }
        }
        return false;
    }

    //Fonction qui enleve des favoris un bien en parametre
    private function removeBienInFavoris(Biens $biens)
    {
        foreach ($this->favoris as $bienFavoris) {
            if ($biens->getId() === $bienFavoris->getId()) {
                unset($this->favoris[key($this->favoris)]);
            }
        }
        return false;
    }

    //Fonction qui affiche les favoris et qui permet de les envoyer par mail
    #[Route('/voirfavoris', name: 'app_view_favoris')]
    public function voirLesFavoris(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $this->setupFavoris($request);
        $favoris = new Favoris();
        $form = $this->createForm(AddFavorisType::class, $favoris);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new TemplatedEmail())
            ->from('saferprojectur1@gmail.com')
            ->to($favoris->getMail())
            ->subject('Vos biens favoris')
            ->htmlTemplate('emails/favoris.html.twig')
            ->context([
                'favoris' => $this->favoris,
            ]);

        $mailer->send($email);

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
    
    //Fonction utilisé par le site pour enlever un bien en favoris (utilisé avec ajax)
    #[Route('/removefavoris/{id}', name: 'app_remove_favoris')]
    public function enleverUnFavoris(Request $request, Biens $bien): Response
    {
        $this->setupFavoris(null);
        $this->removeBienInFavoris($bien);
        $this->finishFavoris();
        $response = new JsonResponse();
        $response->setData([
            'message' => 'Le bien ' . $bien->getIntitule() . ' a été retiré de la liste des favoris.',
        ]);
        return $response;
    }


    //Fonction utilisé par le site pour ajouter un bien en favoris (utilisé avec ajax)    
    #[Route('/addfavoris/{id}', name: 'app_add_favoris')]
    public function ajouternFavoris(Request $request, Biens $bien): Response
    {
        $this->setupFavoris(null);
        array_push($this->favoris, $bien);
        $this->finishFavoris();
        $response = new JsonResponse();
        $response->setData([
            'message' => 'Le bien ' . $bien->getIntitule() . ' a été ajouté de la liste des favoris.',
        ]);
        return $response;
        //return $this->redirect($request->query->get('routeRedirection'));
    }

    //Fonction qui affiche les biens d"une catégorie donner en parametre
    #[Route('/categorie/{id}', name: 'app_categorie')]
    public function categorie(Request $request, ManagerRegistry $doctrine, Categories $categorie): Response
    {    
        $this->setupFavoris($request);
        if($categorie==null){ 
            //Ne devrait pas arriver car les liens de la topbar ne sont creer que si il existe des categories, mais dans le doute
            throw $this->createNotFoundException('Aucune categorie existante');
        }
        $biens = $categorie->getBiens();
        $form = $this->createForm(SearchBiensCriteriaType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $formdata = $form->getData(); //Renvoie un tableau ! A savoir que avec un objet le get data n'aurait pas renvoye les morceau du form non mappe et aurait du etre recuperer individuellement
            $biens = $doctrine->getRepository(Biens::class)->goodsfromIdandForm($categorie->getId(),$formdata);
        }

        return $this->render('homepage/categorie.html.twig', [
            'biens' => $biens,
            'categorie' => $categorie,
            'favoris' => $this->favoris,
            'routeRedirection' => rawurlencode($request->getRequestUri()),
            'form' => $form,
        ]);
    }

    //fonction qui affiche un formulaire de contact
    #[Route('/formulaireContact', name: 'app_form_contact')]
    public function formulaireContact(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setDate(new \DateTime());
            $entityManager->persist($contact);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre demande a été receptioné.');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('homepage/contactForm.html.twig', [
            'formContact' =>  $form->createView(),
        ]);
    }
}

