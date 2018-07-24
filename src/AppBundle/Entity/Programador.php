<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="programador",
 *           uniqueConstraints={
 *                              @ORM\UniqueConstraint(name="programador_ci_unique",columns={"ci"}),
 *                              @ORM\UniqueConstraint(name="programador_nombre_apellidos_unique",columns={"nombre","apellido1","apellido2"})}) * 
 *                             })
 * @UniqueEntity(fields={"ci"})
 * @UniqueEntity(fields={"nombre", "apellido1", "apellido2"})
 * @ORM\HasLifecycleCallbacks
 */
class Programador {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=11, unique=true, nullable=false )
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")    
     * @Assert\Length(min=11, max=11, exactMessage="Debe estar formado por {{ limit }} digitos")
     */
    private $ci;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $apellido1;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $apellido2;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $fecha_nacimiento;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     * @Assert\Choice(choices={"M","F"}, message="Debe seleccionar el sexo")
     */
    private $sexo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $certificado;

    /**
     * @ORM\ManyToOne(targetEntity="SistemaOperativo")
     * @ORM\JoinColumn(name="sistema_id", referencedColumnName="id")
     */
    protected $sistema_operativo;

    /**
     * @ORM\ManyToMany(targetEntity="LenguajeProgramacion")
     * @ORM\JoinTable(name="lenguajes_preferidos", 
     *           joinColumns={@ORM\JoinColumn(name="programador_id", referencedColumnName="id")},
     *           inverseJoinColumns={@ORM\JoinColumn(name="lenguaje_programacion_id",referencedColumnName="id")})
     * @Assert\Count(min=1, max=3, minMessage="Debe seleccionar al menos {{ limit }} lenguajes", maxMessage="Debe  seleccionar a lo sumo {{ limit }} lenguajes")* 
     */
    protected $lenguajes_programacion;

    /**
     * @ORM\OneToMany(targetEntity="Proyecto", mappedBy="programador",cascade={"all"})
     */
    protected $proyectos;

    // pasos para trabajar con ficheros de tipo image

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto_path;

    /**
     * Set foto_path
     *
     * @param string $foto_path
     * @return Pelicula
     */
    public function setFotoPath($foto_path) {
        $this->foto_path = $foto_path;

        return $this;
    }

    /**
     * Get foto_path
     *
     * @return string 
     */
    public function getFotoPath() {
        return $this->foto_path;
    }

    private $foto_path_temp;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $foto_file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFotoFile(UploadedFile $file = null) {
        $this->foto_file = $file;
        // check if we have an old image path
        if (isset($this->foto_path)) {
            // store the old name to delete after the update
            $this->foto_path_temp = $this->foto_path;
            $this->foto_path = null;
        } else {
            $this->foto_path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getFotoFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->foto_path = $filename . '.' . $this->getFotoFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getFotoFile()) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFotoFile()->move($this->getUploadRootDir(), $this->foto_path);
        // check if we have an old image
        if (isset($this->foto_path_temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->foto_path_temp);
            // clear the temp image path
            $this->foto_path_temp = null;
        }
        $this->foto_file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFotoFile() {
        return $this->foto_file;
    }

    public function getAbsolutePath() { //direccion real de la foto para insertarla y eliminarla
        return null === $this->foto_path ? null : $this->getUploadRootDir() . '/' . $this->foto_path;
    }

    public function getWebPath() { // url donde va a estar la foto para acceder x la web
        return null === $this->foto_path ? null : $this->getUploadDir() . '/' . $this->foto_path;
    }

    protected function getUploadRootDir() {//direccion completa de la carpeta donde se guardaran las fotos
        return __DIR__ . '/../../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() { // nombre de la carpeta donde se guardaran las fotos
        return 'media/image/uploads/programador';
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->lenguajes_programacion = new \Doctrine\Common\Collections\ArrayCollection();
         $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getEdad() {
        return (int) ($this->getFechaNacimiento()->diff(new \DateTime())->format("%Y"));
    }

    public function getNombreCompleto() {
        return $this->getNombre() . " " . $this->getApellido1() . " " . $this->getApellido2();
    }

    public function getSexoDescripcion() {
        return ($this->sexo == "M" || $this->sexo == "m") ? "Masculino" : "Femenino";
    }

    public function __toString() {
        return $this->getNombreCompleto();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Programador
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set sexo
     *
     * @param integer $sexo
     * @return Programador
     */
    public function setSexo($sexo) {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return integer 
     */
    public function getSexo() {
        return $this->sexo;
    }

    /**
     * Set certificado
     *
     * @param boolean $certificado
     * @return Programador
     */
    public function setCertificado($certificado) {
        $this->certificado = $certificado;

        return $this;
    }

    /**
     * Get certificado
     *
     * @return boolean 
     */
    public function getCertificado() {
        return $this->certificado;
    }

    /**
     * Set sistema_operativo
     *
     * @param \UO\Bundle\DemoBundle\Entity\SistemaOperativo $sistemaOperativo
     * @return Programador
     */
    public function setSistemaOperativo(\UO\Bundle\DemoBundle\Entity\SistemaOperativo $sistemaOperativo = null) {
        $this->sistema_operativo = $sistemaOperativo;

        return $this;
    }

    /**
     * Get sistema_operativo
     *
     * @return \UO\Bundle\DemoBundle\Entity\SistemaOperativo 
     */
    public function getSistemaOperativo() {
        return $this->sistema_operativo;
    }

    /**
     * Add lenguajes_programacion
     *
     * @param \UO\Bundle\DemoBundle\Entity\LenguajeProgramacion $lenguajesProgramacion
     * @return Programador
     */
    public function addLenguajesProgramacion(\UO\Bundle\DemoBundle\Entity\LenguajeProgramacion $lenguajesProgramacion) {
        $this->lenguajes_programacion[] = $lenguajesProgramacion;

        return $this;
    }

    /**
     * Remove lenguajes_programacion
     *
     * @param \UO\Bundle\DemoBundle\Entity\LenguajeProgramacion $lenguajesProgramacion
     */
    public function removeLenguajesProgramacion(\UO\Bundle\DemoBundle\Entity\LenguajeProgramacion $lenguajesProgramacion) {
        $this->lenguajes_programacion->removeElement($lenguajesProgramacion);
    }

    /**
     * Get lenguajes_programacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLenguajesProgramacion() {
        return $this->lenguajes_programacion;
    }

    /**
     * Set ci
     *
     * @param string $ci
     * @return Programador
     */
    public function setCi($ci) {
        $this->ci = $ci;

        return $this;
    }

    /**
     * Get ci
     *
     * @return string 
     */
    public function getCi() {
        return $this->ci;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     * @return Programador
     */
    public function setApellido1($apellido1) {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string 
     */
    public function getApellido1() {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     * @return Programador
     */
    public function setApellido2($apellido2) {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string 
     */
    public function getApellido2() {
        return $this->apellido2;
    }

    /**
     * Set fecha_nacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Programador
     */
    public function setFechaNacimiento($fechaNacimiento) {
        $this->fecha_nacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fecha_nacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento() {
        return $this->fecha_nacimiento;
    }

    /**
     * Add proyectos
     *
     * @param \UO\Bundle\DemoBundle\Entity\Proyecto $proyectos
     * @return Programador
     */
    public function addProyecto(\UO\Bundle\DemoBundle\Entity\Proyecto $proyectos) {
        $this->proyectos[] = $proyectos;

        return $this;
    }

    /**
     * Remove proyectos
     *
     * @param \UO\Bundle\DemoBundle\Entity\Proyecto $proyectos
     */
    public function removeProyecto(\UO\Bundle\DemoBundle\Entity\Proyecto $proyectos) {
        $this->proyectos->removeElement($proyectos);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectos() {
        return $this->proyectos;
    }

    public function setProyectos($proyectos) {
        return $this->proyectos = $proyectos;
    }

}
