<?php

namespace App\Twig\Components;

use App\Entity\Biens;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('biens')]
final class BiensComponent
{

    //Composant qui permet d'afficher un bien en fonction de différent paramètre

    public Biens $bien;
    public $favoris = false;
    public ?string $routeRedirection = null;
    public bool $isLinkImage = true;
    public bool $isMail = false;
    public bool $showFavoris = true;
    public $email = null;
    public $isFavorisPage = false;


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