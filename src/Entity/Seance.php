<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $module = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $groupe = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $jour = null;

    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $heureDebut = null;
    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $heureFin = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $salle = null;

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): self { $this->titre = $titre; return $this; }

    public function getModule(): ?string { return $this->module; }
    public function setModule(string $module): self { $this->module = $module; return $this; }

    public function getGroupe(): ?string { return $this->groupe; }
    public function setGroupe(string $groupe): self { $this->groupe = $groupe; return $this; }

    public function getJour(): ?string { return $this->jour; }
    public function setJour(string $jour): self { $this->jour = $jour; return $this; }

    public function getHeureDebut(): ?\DateTimeInterface { return $this->heureDebut; }
    public function setHeureDebut(\DateTimeInterface $heureDebut): self { $this->heureDebut = $heureDebut; return $this; }

    public function getHeureFin(): ?\DateTimeInterface { return $this->heureFin; }
    public function setHeureFin(\DateTimeInterface $heureFin): self { $this->heureFin = $heureFin; return $this; }

    public function getSalle(): ?string { return $this->salle; }
    public function setSalle(string $salle): self { $this->salle = $salle; return $this; }
}