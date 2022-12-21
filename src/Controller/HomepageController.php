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
        //$bien2 = RandomBien();
        //$bien3 = RandomBien();
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'first_good' => $bien1,
            //'second_good' => $bien2,
            //'third_good' => $bien3,
        ]);
    }

    public function RandomBien() : array
    {

    }
}
