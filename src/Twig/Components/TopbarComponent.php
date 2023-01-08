<?php
// src/Components/TopbarComponent.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Repository\CategoriesRepository;
use App\Entity\Categories;

#[AsTwigComponent('topbar')]
class TopbarComponent
{
    //Topbar pour les utilisateur contenant les liens pour naviguer entre les pages et des ressources

    //Je suis le tuto ici : https://symfony.com/bundles/ux-twig-component/current/index.html#fetching-services
    private CategoriesRepository $categoriesRepository;
    public string $contactUrl = 'app_form_contact';
    public string $saferUrl = 'app_homepage';
    public string $favorisUrl = 'app_view_favoris';
    public string $categorieUrl = 'app_categorie';
    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function getAllCategorie()
    {
        return $this->categoriesRepository->findAll();
    }


}