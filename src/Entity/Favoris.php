<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'favoris', targetEntity: Favoriser::class)]
    private Collection $biens_favoriser;

    public function __construct()
    {
        $this->biens_favoriser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Favoriser>
     */
    public function getBiensFavoriser(): Collection
    {
        return $this->biens_favoriser;
    }

    public function addBiensFavoriser(Favoriser $biensFavoriser): self
    {
        if (!$this->biens_favoriser->contains($biensFavoriser)) {
            $this->biens_favoriser->add($biensFavoriser);
            $biensFavoriser->setFavoris($this);
        }

        return $this;
    }

    public function removeBiensFavoriser(Favoriser $biensFavoriser): self
    {
        if ($this->biens_favoriser->removeElement($biensFavoriser)) {
            // set the owning side to null (unless already changed)
            if ($biensFavoriser->getFavoris() === $this) {
                $biensFavoriser->setFavoris(null);
            }
        }

        return $this;
    }
}
