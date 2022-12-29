<?php
// src/Components/TopbarComponent.php
namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Repository\CategoriesRepository;

#[AsTwigComponent('topbar')]
class TopbarComponent
{
    //Je suis le tuto ici : https://symfony.com/bundles/ux-twig-component/current/index.html#fetching-services
    private CategoriesRepository $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function getAll()
    {
        return $this->categoriesRepository->findAll();
    }

}