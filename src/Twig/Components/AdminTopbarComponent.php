<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('admin_topbar')]
final class AdminTopbarComponent
{
    
    public string $saferUrl = 'app_admin';

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

    public function getDropdownBiens()
    {
        $array = ["Ajouter un Bien" => "app_biens_new",
                  "Modifier un Bien" => "app_biens_edit",
                  "Supprimer un Bien" => "app_biens_delete"];
        return $array;
    }

}

