<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsTwigComponent('admin_topbar')]
final class AdminTopbarComponent
{
    
    public string $saferUrl;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->saferUrl = $router->generate('app_admin');
    }

    public function getDropdownCategorie()
    {
        $array = ["Modifier une catégorie" => "app_modifier_categories",
                  "Ajouter une catégorie" => "app_add_categories"];
        return $array;
    }

    public function getDropdownAdministrateur()
    {
        $array = ["Modifier un administrateur" => "app_selectioner_modifier_admin",
                  "Ajouter un administrateur" => "app_add_admin"];
        return $array;
    }

    public function getDropdownFavoris()
    {
        $array = ["Supprimer un favoris" => "app_delete_favoris"];
        return $array;
    }

}

