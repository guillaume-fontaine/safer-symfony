<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Biens;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BiensRepository;

class HomepageController extends AbstractController
{

    #[Route('/homepage', name: 'app_homepage')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $biens = $doctrine->getRepository(Biens::class)->qsqldocusymfony();
        //"id, categorie_id, prix, surface, type, localisation, intitule, descriptif, reference"
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'biens' => $biens,
        ]);
    }

}
