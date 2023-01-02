<?php
// src/Components/TopbarComponent.php
namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsTwigComponent('topbar')]
class TopbarComponent
{
    public string $saferUrl;
    public string $favorisUrl;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->saferUrl = $router->generate('app_homepage');
        $this->favorisUrl = $router->generate('app_view_favoris');
    }
}