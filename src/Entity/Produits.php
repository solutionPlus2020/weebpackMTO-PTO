<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitsRepository")
 */
class Produits
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePrestation", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     * })
     */
    private $typePrestation;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pays", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     * })
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img): void
    {
        $this->img = $img;
    }


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="produit")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Descriptions", mappedBy="produits")
     */
    private $descriptions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fournisseur", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $fournisseur;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->descriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypePrestation(): ?TypePrestation
    {
        return $this->typePrestation;
    }

    public function setTypePrestation(?TypePrestation $typePrestation): self
    {
        $this->typePrestation = $typePrestation;

        return $this;
    }
    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }
   

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getJours(): ?int
    {
        return $this->jours;
    }

    public function setJours(int $jours): self
    {
        $this->jours = $jours;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getOpption(): ?string
    {
        return $this->opption;
    }

    public function setOpption(string $opption): self
    {
        $this->opption = $opption;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }
    /**
     * @param mixed $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * @return Collection|Descriptions[]
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }

    public function addDescription(Descriptions $description): self
    {
        if (!$this->descriptions->contains($description)) {
            $this->descriptions[] = $description;
            $description->setProduits($this);
        }

        return $this;
    }

    public function removeDescription(Descriptions $description): self
    {
        if ($this->descriptions->contains($description)) {
            $this->descriptions->removeElement($description);
            // set the owning side to null (unless already changed)
            if ($description->getProduits() === $this) {
                $description->setProduits(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return (string) $this->title;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }


}
