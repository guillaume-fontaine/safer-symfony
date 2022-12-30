<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConditionalAssetExtension extends AbstractExtension {

    protected $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;

    }

    public function getFunctions()
    {
        return [
            new TwigFunction('image_if', [$this, 'assetIf']),
        ];
    }

    /**
     * Get the path to an asset. If it does not exist, return the path to the
     * fallback path.
     * 
     * @param string $path the path to the asset to display
     * @return string path
     */
    public function assetIf($path)
    {
        
        // Define the path to look for
        $pathToCheck = realpath($this->parameterBag->get('kernel.project_dir') . '/public' )  . $path;

        // If the path does not exist, return the fallback image
        if (!file_exists($pathToCheck))
        {
            return "/image/no_image_avaible.jpg";
        }

        // Return the real image
        return $path;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
       return 'ConditionalAsset';
    }
}