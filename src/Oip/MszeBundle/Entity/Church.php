<?php

namespace Oip\MszeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Church
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oip\MszeBundle\Entity\ChurchRepository")
 */
class Church
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=64)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="text", length=64, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="text", length=64, nullable=true)
     */
    private $longitude;
    
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=64)
     */
    private $foto;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="District", inversedBy="churches", fetch="LAZY")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     */
    protected $district;

    /**
     * @ORM\OneToMany(targetEntity="Mass", mappedBy="church", fetch="LAZY")
     */
    protected $masses;
    
    public function __construct() {
        $this->masses = new ArrayCollection();
    }
    
    private function updateSlug() {
        $this->slug = \Oip\MszeBundle\OipHelpers::makeSlug(
                      ($this->name == null?'':$this->name) . ' ' . 
                      ($this->address == null?'':$this->address) . ' ' . 
                      ($this->description == null?'':$this->description));
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Church
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->updateSlug();
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Church
     */
    public function setAddress($address)
    {
        $this->address = $address;
        $this->updateSlug();
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * Set latitude
     *
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
     /**
     * Set longitude
     *
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Church
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Church
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    
        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set desc
     *
     * @param string $desc
     * @return Church
     */
    public function setDescription($desc)
    {
        $this->description = $desc;
        $this->updateSlug();
    
        return $this;
    }

    /**
     * Get desc
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add masses
     *
     * @param \Oip\MszeBundle\Entity\Mass $masses
     * @return Church
     */
    public function addMasse(\Oip\MszeBundle\Entity\Mass $masses)
    {
        $this->masses[] = $masses;
    
        return $this;
    }

    /**
     * Remove masses
     *
     * @param \Oip\MszeBundle\Entity\Mass $masses
     */
    public function removeMasse(\Oip\MszeBundle\Entity\Mass $masses)
    {
        $this->masses->removeElement($masses);
    }

    /**
     * Get masses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMasses()
    {
        return $this->masses;
    }

    /**
     * Set district
     *
     * @param \Oip\MszeBundle\Entity\District $district
     * @return Church
     */
    public function setDistrict(\Oip\MszeBundle\Entity\District $district = null)
    {
        $this->district = $district;
    
        return $this;
    }

    /**
     * Get district
     *
     * @return \Oip\MszeBundle\Entity\District 
     */
    public function getDistrict()
    {
        return $this->district;
    }
}