<?php

namespace App\Entity;

use App\Repository\LecturaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LecturaRepository::class)]
class Lectura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $FechaComienzo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $FechaFinal = null;

    #[ORM\OneToOne(inversedBy: 'lectura')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Libro $Libro = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getFechaComienzo(): ?\DateTimeInterface
    {
        return $this->FechaComienzo;
    }

    public function setFechaComienzo(?\DateTimeInterface $FechaComienzo): static
    {
        $this->FechaComienzo = $FechaComienzo;

        return $this;
    }

    public function getFechaFinal(): ?\DateTimeInterface
    {
        return $this->FechaFinal;
    }

    public function setFechaFinal(?\DateTimeInterface $FechaFinal): static
    {
        $this->FechaFinal = $FechaFinal;

        return $this;
    }

    public function getLibro(): ?Libro
    {
        return $this->Libro;
    }

    public function setLibro(?Libro $Libro): static
    {
        $this->Libro = $Libro;

        return $this;
    }
}
