<?php

namespace App\Entity;

use App\Repository\LibroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: LibroRepository::class)]
class Libro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $Titulo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $Editorial = null;

    #[ORM\Column(length: 255)]
    private ?string $Autor = null;

    #[ORM\Column(type: Types::BLOB)]
    #[Assert\NotBlank]
    private $Portada = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $FechaPublicacion = null;

    #[ORM\ManyToOne(inversedBy: 'libros')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Language $lengua = null;

    #[ORM\OneToOne(mappedBy: 'book', cascade: ['persist', 'remove'])]
    private ?Lectura $lectura = null;

    public function __construct()
    {
        #$this->lectura = new ArrayCollection();
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

    public function getLectura(): ?Lectura
    {
        return $this->lectura;
    }

    public function setLectura(Lectura $lectura): static
    {
        // set the owning side of the relation if necessary
        if ($lectura->getBook() !== $this) {
            $lectura->setBook($this);
        }

        $this->lectura = $lectura;

        return $this;
    }

}
