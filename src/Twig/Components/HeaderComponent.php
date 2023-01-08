<?php
// src/Components/HeaderComponent.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('header')]
class HeaderComponent
{
    // Composant qui sert au header de toute les pages contenant les ressources bootstrap

    public string $boostrapCDN = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css';
    public string $lastestCompiledJS = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'; 
    public string $jsFavoris = 'https://myapp.localhost/js/favoris.js'; 
   
}