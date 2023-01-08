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
    public $favoris = false;
    public ?string $routeRedirection = null;
    public bool $isLinkImage = true;
    public bool $isMail = false;
    public bool $showFavoris = true;
    public $email = null;

    public function __construct(public UrlGeneratorInterface $router)
    {
    }

    #[ExposeInTemplate('add_url')]
    public function getAddUrl()
    {
        return $this->router->generate('app_add_from_favoris', ['id' => $this->bien->getId(), 'routeRedirection' => $this->routeRedirection]);
    }

    #[ExposeInTemplate('delete_url')]
    public function getDeleteUrl()
    {
        return $this->router->generate('app_remove_from_favoris', ['id' => $this->bien->getId(), 'routeRedirection' => $this->routeRedirection]);
    }

    #[ExposeInTemplate('show_favoris')]
    public function isShowFavoris()
    {
        if($this->isMail){
            return false;
        }
        return $this->showFavoris;
    }

    #[ExposeInTemplate('is_favoris')]
    public function isFavoris()
    {
        if (is_array($this->favoris)) {
            foreach ($this->favoris as $bienFavoris) {
                if ($this->bien->getId() == $bienFavoris->getId()) {
                    return true;
                }
            }
            return false;

        }
        return $this->favoris;
    }

}