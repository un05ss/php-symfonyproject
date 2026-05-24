<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Etudiant::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $etudiant = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $module = null;

    #[ORM\Column(type: 'float')]
    private ?float $valeur = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $groupe = null;

    public function getId(): ?int { return $this->id; }

    public function getEtudiant(): ?Etudiant { return $this->etudiant; }
    public function setEtudiant(?Etudiant $etudiant): self { $this->etudiant = $etudiant; return $this; }

    public function getModule(): ?string { return $this->module; }
    public function setModule(string $module): self { $this->module = $module; return $this; }

    public function getValeur(): ?float { return $this->valeur; }
    public function setValeur(float $valeur): self { $this->valeur = $valeur; return $this; }

    public function getGroupe(): ?string { return $this->groupe; }
    public function setGroupe(string $groupe): self { $this->groupe = $groupe; return $this; }
}