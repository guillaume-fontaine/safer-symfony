<?php
// src/Components/TopbarComponent.php
namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\CategoriesRepository;
use App\Entity\Categories;

#[AsTwigComponent('topbar')]
class TopbarComponent
{

    //Je suis le tuto ici : https://symfony.com/bundles/ux-twig-component/current/index.html#fetching-services
    private CategoriesRepository $categoriesRepository;
    private UrlGeneratorInterface $router;
    public string $saferUrl;
    public string $favorisUrl;

    public function __construct(UrlGeneratorInterface $router, CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->router = $router;
        $this->saferUrl = $router->generate('app_homepage');
        $this->favorisUrl = $router->generate('app_view_favoris');
    }

    public function getAllCategorie()
    {
        return $this->categoriesRepository->findAll();
    }

    public function getLink(Categories $categorie)
    {
        return $this->router->generate('app_categorie', ['id' => $categorie->getId()]);
    }

}