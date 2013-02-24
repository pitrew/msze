<?php

namespace Oip\MszeBundle\LocalEntity;


/**
 * City
 */
class Search
{
    private $pattern;


    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getPattern()
    {
        return $this->pattern;
    }


}