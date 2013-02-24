<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //TODO: add autocomplete form
        
        return $this->render('OipMszeBundle:Default:index.html.twig');
    }
}
