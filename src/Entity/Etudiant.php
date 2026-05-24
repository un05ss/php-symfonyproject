<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:50)]
    private ?string $nom = null;

    #[ORM\Column(type:"string", length:50)]
    private ?string $prenom = null;

    #[ORM\Column(type:"string", length:10)]
    private ?string $groupe = null;

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }
    public function getGroupe(): ?string { return $this->groupe; }
    public function setGroupe(string $groupe): self { $this->groupe = $groupe; return $this; }
}