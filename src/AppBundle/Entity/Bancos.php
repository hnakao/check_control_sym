<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Bancos
 *
 * @ORM\Table(name="bancos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BancosRepository")
 */
class Bancos {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_banco", type="string", length=255)
     */
    private $nombreBanco;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto_path;

    
    public function __toString() {
        return $this->getNombreBanco();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombreBanco
     *
     * @param string $nombreBanco
     *
     * @return Bancos
     */
    public function setNombreBanco($nombreBanco) {
        $this->nombreBanco = $nombreBanco;

        return $this;
    }

    /**
     * Get nombreBanco
     *
     * @return string
     */
    public function getNombreBanco() {
        return $this->nombreBanco;
    }

    /**
     * Set fotoPath
     *
     * @param string $fotoPath
     *
     * @return Bancos
     */
    public function setFotoPath($fotoPath) {
        $this->foto_path = $fotoPath;

        return $this;
    }

    /**
     * Get fotoPath
     *
     * @return string
     */
    public function getFotoPath() {
        return $this->foto_path;
    }

   
}
