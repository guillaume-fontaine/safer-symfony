<?php

namespace App\Entity;

use App\Repository\BiensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BiensRepository::class)]
class Biens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 2)]
    private ?string $surface = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 10)]
    private ?string $localisation = null;

    #[ORM\Column(length: 200)]
    private ?string $intitule = null;

    #[ORM\Column(length: 200)]
    private ?string $descriptif = null;

    #[ORM\Column(length: 15)]
    private ?string $reference = null;

    #[ORM\ManyToOne(inversedBy: 'biens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categorie = null;

    #[ORM\OneToMany(mappedBy: 'biens', targetEntity: Favoriser::class)]
    private Collection $favorisers;

    public function __construct()
    {
        $this->favorisers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(string $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Favoriser>
     */
    public function getFavorisers(): Collection
    {
        return $this->favorisers;
    }

    public function addFavoriser(Favoriser $favoriser): self
    {
        if (!$this->favorisers->contains($favoriser)) {
            $this->favorisers->add($favoriser);
            $favoriser->setBiens($this);
        }

        return $this;
    }

    public function removeFavoriser(Favoriser $favoriser): self
    {
        if ($this->favorisers->removeElement($favoriser)) {
            // set the owning side to null (unless already changed)
            if ($favoriser->getBiens() === $this) {
                $favoriser->setBiens(null);
            }
        }

        return $this;
    }
}
