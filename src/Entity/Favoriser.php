<?php

namespace App\Entity;

use App\Repository\FavoriserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriserRepository::class)]
class Favoriser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'biens_favoriser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Favoris $favoris = null;

    #[ORM\ManyToOne(inversedBy: 'favorisers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Biens $biens = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFavoris(): ?Favoris
    {
        return $this->favoris;
    }

    public function setFavoris(?Favoris $favoris): self
    {
        $this->favoris = $favoris;

        return $this;
    }

    public function getBiens(): ?Biens
    {
        return $this->biens;
    }

    public function setBiens(?Biens $biens): self
    {
        $this->biens = $biens;

        return $this;
    }
}
