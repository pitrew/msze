<?php

namespace Oip\MszeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * District
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oip\MszeBundle\Entity\DistrictRepository")
 */
class District
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
     * @ORM\OneToMany(targetEntity="Church", mappedBy="district", fetch="LAZY")
     */
    protected $churches;
    
    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="districts", fetch="LAZY")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    protected $city;
    
    public function __construct() {        
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
        $this->slug = $this->name; //TODO: make slug
    
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

       /**
     * Set city
     *
     * @param \Oip\MszeBundle\Entity\City $city
     * @return District
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
    
    public function isDefault()
    {
        return empty($this->name);
    }
}