<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Biens;
use App\Form\FavoriserType;
use Doctrine\Persistence\ManagerRegistry;

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