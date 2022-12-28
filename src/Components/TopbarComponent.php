<?php
// src/Components/TopbarComponent.php
namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('topbar')]
class TopbarComponent
{
    public string $current = 'Homepage';
    public string $menu1 = 'Homepage';
    public string $menu2 = 'Biens'; //ici une page categorie
    public string $menu4 = 'ChercherBiens'; //formulaire multicritere
    public string $menu3 = 'Contact'; //page de contact
    public string $message;// C'est un reste du tutoriel, je le nettoyerais s'il sert a rien
    //Techniquement toutes ces variables sont useless vu que l'on pourrait changer la valeur de current,
    //elles servent de rappel a ce que peut contenir current et l'on change current via Twig
}