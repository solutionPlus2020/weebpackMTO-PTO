<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaysRepository")
 */
class Pays
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libPay;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produits", mappedBy="typePrestation")
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibPay(): ?string
    {
        return $this->libPay;
    }

    public function setLibPay(string $libPay): self
    {
        $this->libPay = $libPay;

        return $this;
    }
    /**
     * @return Collection|Produits[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }
    /**
     * @param mixed $produits
     */
    public function setProduits($produits): void
    {
        $this->produits = $produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setTypePrestation($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getTypePrestation() === $this) {
                $produit->setTypePrestation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->libPay;
    }



}
