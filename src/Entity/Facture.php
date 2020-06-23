<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $total = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FactureArticle", mappedBy="factures", orphanRemoval=true)
     */
    private $factureArticles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $id_facture;

    public function __construct()
    {
        $this->factureArticles = new ArrayCollection();
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->id_facture;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|FactureArticle[]
     */
    public function getFactureArticles(): Collection
    {
        return $this->factureArticles;
    }

    public function addFactureArticle(FactureArticle $factureArticle): self
    {
        if (!$this->factureArticles->contains($factureArticle)) {
            $this->factureArticles[] = $factureArticle;
            $factureArticle->setFactures($this);
        }

        return $this;
    }

    public function removeFactureArticle(FactureArticle $factureArticle): self
    {
        if ($this->factureArticles->contains($factureArticle)) {
            $this->factureArticles->removeElement($factureArticle);
            // set the owning side to null (unless already changed)
            if ($factureArticle->getFactures() === $this) {
                $factureArticle->setFactures(null);
            }
        }

        return $this;
    }

    public function getIdFacture(): ?string
    {
        return $this->id_facture;
    }

    public function setIdFacture(string $id_facture): self
    {
        $this->id_facture = $id_facture;

        return $this;
    }
}
