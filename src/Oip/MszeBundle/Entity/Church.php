<?php

namespace Oip\MszeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Church
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @ORM\Column(name="desc", type="string", length=255)
     */
    private $desc;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="churches")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    protected $city;

    /**
     * @ORM\OneToMany(targetEntity="Mass", mappedBy="church")
     */
    protected $masses;
    
    public function __construct() {
        $this->masses = new ArrayCollection();
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
    public function setDesc($desc)
    {
        $this->desc = $desc;
    
        return $this;
    }

    /**
     * Get desc
     *
     * @return string 
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set city
     *
     * @param \Oip\MszeBundle\Entity\City $city
     * @return Church
     */
    public function setCity(\Oip\MszeBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \Oip\MszeBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
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
}