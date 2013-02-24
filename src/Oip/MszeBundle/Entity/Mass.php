<?php

namespace Oip\MszeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mass
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oip\MszeBundle\Entity\MassRepository")
 */
class Mass
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
     * @var integer
     *
     * @ORM\Column(name="start_time", type="integer")
     */
    private $start_time;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="string", length=255)
     */
    private $details;

    /**
     * @var boolean
     *
     * @ORM\Column(name="day_mon", type="boolean")
     */
    private $day_mon;

    /**
     * @var boolean
     *
     * @ORM\Column(name="day_tue", type="boolean")
     */
    private $day_tue;

    /**
     * @var boolean
     *
     * @ORM\Column(name="day_wed", type="boolean")
     */
    private $day_wed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="day_thu", type="boolean")
     */
    private $day_thu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="day_fri", type="boolean")
     */
    private $day_fri;

    /**
     * @var boolean
     *
     * @ORM\Column(name="day_sat", type="boolean")
     */
    private $day_sat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="day_sun", type="boolean")
     */
    private $day_sun;
    
    /**
     * @ORM\ManyToOne(targetEntity="Church", inversedBy="masses", fetch="LAZY")
     * @ORM\JoinColumn(name="church_id", referencedColumnName="id")
     */
    protected $church;


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
     * Set start_time
     *
     * @param integer $startTime
     * @return Mass
     */
    public function setStartTime($startTime)
    {
        $this->start_time = $startTime;
    
        return $this;
    }

    /**
     * Get start_time
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return Mass
     */
    public function setDetails($details)
    {
        $this->details = $details;
    
        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set day_mon
     *
     * @param boolean $dayMon
     * @return Mass
     */
    public function setDayMon($dayMon)
    {
        $this->day_mon = $dayMon;
    
        return $this;
    }

    /**
     * Get day_mon
     *
     * @return boolean 
     */
    public function getDayMon()
    {
        return $this->day_mon;
    }

    /**
     * Set day_tue
     *
     * @param boolean $dayTue
     * @return Mass
     */
    public function setDayTue($dayTue)
    {
        $this->day_tue = $dayTue;
    
        return $this;
    }

    /**
     * Get day_tue
     *
     * @return boolean 
     */
    public function getDayTue()
    {
        return $this->day_tue;
    }

    /**
     * Set day_wed
     *
     * @param boolean $dayWed
     * @return Mass
     */
    public function setDayWed($dayWed)
    {
        $this->day_wed = $dayWed;
    
        return $this;
    }

    /**
     * Get day_wed
     *
     * @return boolean 
     */
    public function getDayWed()
    {
        return $this->day_wed;
    }

    /**
     * Set day_thu
     *
     * @param boolean $dayThu
     * @return Mass
     */
    public function setDayThu($dayThu)
    {
        $this->day_thu = $dayThu;
    
        return $this;
    }

    /**
     * Get day_thu
     *
     * @return boolean 
     */
    public function getDayThu()
    {
        return $this->day_thu;
    }

    /**
     * Set day_fri
     *
     * @param boolean $dayFri
     * @return Mass
     */
    public function setDayFri($dayFri)
    {
        $this->day_fri = $dayFri;
    
        return $this;
    }

    /**
     * Get day_fri
     *
     * @return boolean 
     */
    public function getDayFri()
    {
        return $this->day_fri;
    }

    /**
     * Set day_sat
     *
     * @param boolean $daySat
     * @return Mass
     */
    public function setDaySat($daySat)
    {
        $this->day_sat = $daySat;
    
        return $this;
    }

    /**
     * Get day_sat
     *
     * @return boolean 
     */
    public function getDaySat()
    {
        return $this->day_sat;
    }

    /**
     * Set day_sun
     *
     * @param boolean $daySun
     * @return Mass
     */
    public function setDaySun($daySun)
    {
        $this->day_sun = $daySun;
    
        return $this;
    }

    /**
     * Get day_sun
     *
     * @return boolean 
     */
    public function getDaySun()
    {
        return $this->day_sun;
    }

    /**
     * Set church
     *
     * @param \Oip\MszeBundle\Entity\Church $church
     * @return Mass
     */
    public function setChurch(\Oip\MszeBundle\Entity\Church $church = null)
    {
        $this->church = $church;
    
        return $this;
    }

    /**
     * Get church
     *
     * @return \Oip\MszeBundle\Entity\Church 
     */
    public function getChurch()
    {
        return $this->church;
    }
}