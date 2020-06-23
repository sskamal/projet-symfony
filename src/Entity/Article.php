<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FactureArticle", mappedBy="articles", orphanRemoval=true)
     */
    private $factureArticles;

    public function __construct()
    {
        $this->factureArticles = new ArrayCollection();
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->description;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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
            $factureArticle->setArticles($this);
        }

        return $this;
    }

    public function removeFactureArticle(FactureArticle $factureArticle): self
    {
        if ($this->factureArticles->contains($factureArticle)) {
            $this->factureArticles->removeElement($factureArticle);
            // set the owning side to null (unless already changed)
            if ($factureArticle->getArticles() === $this) {
                $factureArticle->setArticles(null);
            }
        }

        return $this;
    }
}
