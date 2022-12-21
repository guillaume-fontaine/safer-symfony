<?php
// src/Components/HeaderComponent.php
namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('header')]
class HeaderComponent
{
    public string $boostrapCDN = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css';
    public string $jQuerylibrary = 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js';
    public string $lastestCompiledJS = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'; 
   
}