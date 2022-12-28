<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Biens;
use Doctrine\Persistence\ManagerRegistry;

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

}
