<?php

namespace App\Entity;

use App\Repository\LibroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibroRepository::class)]
class Libro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Titulo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Editorial = null;

    #[ORM\Column(length: 255)]
    private ?string $Autor = null;

    #[ORM\Column(type: Types::BLOB)]
    private $Portada = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $FechaPublicacion = null;

    #[ORM\ManyToOne(inversedBy: 'libros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $lengua = null;

    #[ORM\OneToOne(targetEntity: Lectura::class, mappedBy: 'Libro', orphanRemoval: true)]
    private Collection $lectura;

    public function __construct()
    {
        $this->lectura = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->Titulo;
    }

    public function setTitulo(string $Titulo): static
    {
        $this->Titulo = $Titulo;

        return $this;
    }

    public function getEditorial(): ?string
    {
        return $this->Editorial;
    }

    public function setEditorial(?string $Editorial): static
    {
        $this->Editorial = $Editorial;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->Autor;
    }

    public function setAutor(string $Autor): static
    {
        $this->Autor = $Autor;

        return $this;
    }

    public function getPortada()
    {
        return $this->Portada;
    }

    public function setPortada($Portada): static
    {
        $this->Portada = $Portada;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->FechaPublicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $FechaPublicacion): static
    {
        $this->FechaPublicacion = $FechaPublicacion;

        return $this;
    }

    public function getLengua(): ?Language
    {
        return $this->lengua;
    }

    public function setLengua(?Language $lengua): static
    {
        $this->lengua = $lengua;

        return $this;
    }

    /**
     * @return Collection<int, Lectura>
     */
    public function getLectura(): Collection
    {
        return $this->lectura;
    }

    public function addLectura(Lectura $lectura): static
    {
        if (!$this->lectura->contains($lectura)) {
            $this->lectura->add($lectura);
            $lectura->setLibro($this);
        }

        return $this;
    }

    public function removeLectura(Lectura $lectura): static
    {
        if ($this->lectura->removeElement($lectura)) {
            // set the owning side to null (unless already changed)
            if ($lectura->getLibro() === $this) {
                $lectura->setLibro(null);
            }
        }

        return $this;
    }
}
