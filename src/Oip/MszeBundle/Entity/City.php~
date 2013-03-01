<?php

namespace Oip\MszeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oip\MszeBundle\Entity\CityRepository")
 */
class City
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
     * @ORM\Column(name="district", type="string", length=64, nullable=true)
     */
    private $district;

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
     * @ORM\OneToMany(targetEntity="Church", mappedBy="city")
     */
    protected $churches;
    
    public function __construct() {
        $this->foto = 'city';
        $this->churches = new ArrayCollection();
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
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = $this->name . ' ' . $this->district;
    
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
     * Set district
     *
     * @param string $district
     * @return City
     */
    public function setDistrict($district)
    {
        $this->district = $district;
        $this->slug = $this->name . ' ' . $this->district;
        
        return $this;
    }

    /**
     * Get district
     *
     * @return string 
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return City
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
     * @return City
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
     * Add churches
     *
     * @param \Oip\MszeBundle\Entity\Church $churches
     * @return City
     */
    public function addChurche(\Oip\MszeBundle\Entity\Church $churches)
    {
        $this->churches[] = $churches;
    
        return $this;
    }

    /**
     * Remove churches
     *
     * @param \Oip\MszeBundle\Entity\Church $churches
     */
    public function removeChurche(\Oip\MszeBundle\Entity\Church $churches)
    {
        $this->churches->removeElement($churches);
    }

    /**
     * Get churches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChurches()
    {
        return $this->churches;
    }
}