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
     * @ORM\OneToMany(targetEntity="District", mappedBy="city", fetch="LAZY")
     */
    protected $districts;
    
    public function __construct() {
        $this->foto = 'city';
        $this->districts = new ArrayCollection();
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
        $this->name = trim($name);
        $this->slug = \Oip\MszeBundle\OipHelpers::makeSlug($this->name);
    
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
     * Get districts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDistricts()
    {
        return $this->districts;
    }

    /**
     * Add districts
     *
     * @param \Oip\MszeBundle\Entity\Church $districts
     * @return City
     */
    public function addDistrict(\Oip\MszeBundle\Entity\District $districts)
    {
        $this->districts[] = $districts;
    
        return $this;
    }

    /**
     * Remove districts
     *
     * @param \Oip\MszeBundle\Entity\Church $districts
     */
    public function removeDistrict(\Oip\MszeBundle\Entity\District $districts)
    {
        $this->districts->removeElement($districts);
    }
}