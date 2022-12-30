<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Biens;
use App\Entity\Categories;
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

    #[Route('/categorie', name: 'app_Categorie')]
    public function categorie(ManagerRegistry $doctrine): Response
    {
        
        $request = Request::createFromGlobals();
        $request->getPathInfo();// the URI being requested (e.g. /about) minus any query parameters //cp colle de la doc
        $id = $request->query->get('id'); // transforme le type de requete vers "int"
        if($id==null){ 
            //Ne devrait pas arriver car les liens de la topbar ne sont creer que si il existe des categories, mais dans le doute
            throw $this->createNotFoundException('Aucune categorie existante');
        }
        $categorie = $doctrine->getRepository(Categories::class)->find($id);
        $biens = $doctrine->getRepository(Biens::class)->allGoodsfromCategorie($id);
        return $this->render('homepage/categorie.html.twig', [
            'biens' => $biens,
            'categorie' => $categorie,
        ]);
    }
}
