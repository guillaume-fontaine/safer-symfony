<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    #[Route('/homepage', name: 'app_homepage')]
    public function index(): Response
    {
        $bien1 = ['intitule','cat','desc','prix','loca','surface'];
        $bien2 = ['intitule','cat','desc','prix','loca','surface'];
        //$bien3 = RandomBien();
        $biens = [$bien1, $bien2];
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'biens' => $biens,
        ]);
    }

    public function RandomBien() : array
    {

    }
}
