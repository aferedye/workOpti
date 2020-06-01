<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidatureRepository")
 */
class Candidature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Society", inversedBy="candidature", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Society;

    /**
     * @ORM\Column(type="datetime")
     */
    private $nouvellecandidature;

    /**
     * @ORM\Column(type="datetime")
     */
    private $relance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSociety(): ?Society
    {
        return $this->Society;
    }

    public function setSociety(Society $Society): self
    {
        $this->Society = $Society;

        return $this;
    }

    public function getNouvellecandidature(): ?\DateTimeInterface
    {
        return $this->nouvellecandidature;
    }

    public function setNouvellecandidature(\DateTimeInterface $nouvellecandidature): self
    {
        $this->nouvellecandidature = $nouvellecandidature;

        return $this;
    }

    public function getRelance(): ?\DateTimeInterface
    {
        return $this->relance;
    }

    public function setRelance(\DateTimeInterface $relance): self
    {
        $this->relance = $relance;

        return $this;
    }
}
