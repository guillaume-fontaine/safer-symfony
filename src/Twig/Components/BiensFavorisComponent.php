<?php

namespace App\Twig\Components;

use App\Entity\Biens;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('biens_favoris')]
final class BiensFavorisComponent
{

    public Biens $bien;

    public function __construct(public UrlGeneratorInterface $router)
    {
    }

    #[ExposeInTemplate('delete_url')]
    public function getDeleteUrl(){
        return $this->router->generate('app_remove_from_favoris', ['id' => $this->bien->getId()]);
    }

}
