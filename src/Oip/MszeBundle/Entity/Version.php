<?php

namespace Oip\MszeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Church
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oip\MszeBundle\Entity\VersionRepository")
 */
class Version
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
     * @ORM\Column(name="val", type="string")
     */
    private $val;
    
    public function __construct() {
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
    public function setVal($val)
    {
        $this->val = $val;
        
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getVal()
    {
        return $this->val;
    }

}