<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oip\MszeBundle\Entity\City;

class ShowController extends Controller
{
    public function citiesAction()
    {
        $pattern = '';
        if ($this->getRequest()->query->has('s'))
        {
            $pattern = $this->getRequest()->query->get('s');
        }
        
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:City');
        $cities = $repo->findByAny($pattern);
        
        return $this->render('OipMszeBundle:Show:cities.html.twig', array('cities' => $cities, 'pattern' => $pattern ));
    }
    
    public function cityAction($id)
    {
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:City');
        $city = $repo->find($id);
        $hours = $repo->findAllHours($id, 0, 0, 0, 0, 0, 0, 0);
        
        if ($city != null)
        {
            //$city->getChurches(); //load them for now only
        }
        else
        {
            return $this->forward('OipMszeBundle:Show:cities');
        }
        
        return $this->render('OipMszeBundle:Show:city.html.twig', array('city' => $city, 'hours' => $hours ));
    }
    
    public function churchesAction($city_id)
    {
        $pattern = '';
        if ($this->getRequest()->query->has('s'))
        {
            $pattern = $this->getRequest()->query->get('s');
        }
        
        if ($city_id == -1)
        {
            throw $this->createNotFoundException("City not found");
        }
        
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:Church');
        $churches = $repo->findByCity($city_id);
        
        return $this->render('OipMszeBundle:Show:churches.html.twig', array('churches' => $churches, 'pattern' => $pattern ));
    }
    
    public function churchAction($city_id, $id)
    {
        if ($city_id == -1 || $id == -1)
        {
            throw $this->createNotFoundException("City or curch not found");
        }
        
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:Church');
        $church = $repo->find($id);
        if ($church != null)
        {
            //$church->getMasses(); //load them for now only
        }
        else
        {
            return $this->forward('OipMszeBundle:Show:churches');
        }
        
        return $this->render('OipMszeBundle:Show:church.html.twig', array('church' => $church ));
    }
    
    public function massesAction($church_id)
    {
        if ($church_id == -1)
        {
            throw $this->createNotFoundException("Church not found");
        }
        
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:Mass');
        $masses = $repo->findByChurch($church_id);
        
        return $this->render('OipMszeBundle:Show:masses.html.twig', array('masses' => $masses ));
    }
}
