<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ShowController extends Controller
{    
      
    public function citiesAction($_format)
    {   
        $pattern = '';
        $small = false;
        if ($this->getRequest()->query->has('s'))
        {
            $pattern = $this->getRequest()->query->get('s');
        }
        
        if ($this->getRequest()->query->has('small'))
        {
            $small = $this->getRequest()->query->get('small');
        }
        
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:City');        
        //$cities = $repo->findByAny(urldecode($pattern));
        $cities = $repo->findByAny($pattern);
        
        $result = array();
        if ($small == true)
        {
            for ($x = 0; $x < sizeof($cities); $x++)
            {
                $result[$x] = array();
                //'id' => $cities[$x]->getId(), 'name' => $cities[$x]->getName()
                $result[$x][] = $cities[$x]->getId();
                $result[$x][] = $cities[$x]->getName();
            }
        }
        else
        {
            for ($x = 0; $x < sizeof($cities); $x++)
            {
                $result[$x] = array('id' => $cities[$x]->getId(), 'name' => $cities[$x]->getName());
            }
        }
        
        if ($_format == 'json' || $_format == 'xml')
        {
            $serializer = $this->container->get('serializer');
            return new Response($serializer->serialize($result, $_format));
        }
        else
        {
            return $this->render('OipMszeBundle:Show:cities.html.twig', array('cities' => $result, 'pattern' => $pattern ));
        }
    }
    
    public function cityAction($_format)
    {
        if (!$this->getRequest()->query->has('id'))
        {
            return $this->forward('OipMszeBundle:Show:cities');
        }
        $id = $this->getRequest()->query->get('id');
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:City');
        $city = $repo->find($id);
        
        $days = array('Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota');
        $hours = array();
        $hours[0] = $repo->findAllHours($id, 'sun');
        $hours[1] = $repo->findAllHours($id, 'mon');
        $hours[2] = $repo->findAllHours($id, 'tue');
        $hours[3] = $repo->findAllHours($id, 'wed');
        $hours[4] = $repo->findAllHours($id, 'thu');
        $hours[5] = $repo->findAllHours($id, 'fri');
        $hours[6] = $repo->findAllHours($id, 'sat');
        
        
        if ($city != null)
        {
            //$city->getChurches(); //load them for now only
        }
        else
        {
            return $this->forward('OipMszeBundle:Show:cities');
        }
        
        if ($_format == 'json' || $_format == 'xml')
        {
            $serializer = $this->container->get('serializer');
            return new Response($serializer->serialize(array('city' => $city, 'hours' => $hours ), $_format));
        }
        else
        {
            return $this->render('OipMszeBundle:Show:city.html.twig', array('city' => $city, 'hours' => $hours, 'days' => $days ));
        }
        
    }
    
    public function churchesAction($city_id)
    {
        $pattern = '';
        if ($this->getRequest()->query->has('s'))
        {
            $pattern = $this->getRequest()->query->get('s');
        }
        
        $repoCity = $this->getDoctrine()->getRepository('OipMszeBundle:City');
        $city = $repoCity->findOneById($city_id);
        if ($city == null) {
            throw $this->createNotFoundException("City not found");
        }
        $districts = $city->getDistricts();
        $districtsArray = array();
        foreach ($districts as $district)
        {
            $churchesArray = array();
            foreach ($district->getChurches() as $church) {
                $churchesArray[$church->getId()] = array( 
                    'name' => $church->getName(),
                    'address' => $church->getAddress(),
                    'lat' => $church->getLatitude(),
                    'lng' => $church->getLongitude(),
                    'desc' => $church->getDescription(),
                    'www' => $church->getWWW(),
                );
            }
            if (sizeof($churchesArray) > 0) {
                $districtsArray[$district->getId()] = 
                        array('id' => $district->getId(), 
                            'name' => $district->getName(), 
                            'churches' => $churchesArray);
            }
        }
        
        return $this->render('OipMszeBundle:Show:churches.html.twig', 
                array(  'city_id' => $city->getId(),
                        'result' => $districtsArray, 
                        'pattern' => $pattern ));
    }
    
    public function churchAction($_format, $church_id)
    {
        if ($church_id == -1)
        {
            throw $this->createNotFoundException("City or curch not found");
        }
        
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:Church');
        $church = $repo->find($church_id);
        if ($church != null)
        {
            //$church->getMasses(); //load them for now only
        }
        else
        {
            //return $this->forward('OipMszeBundle:Show:churches');
        }
        
        
        if ($_format == 'json' || $_format == 'xml')
        {
            throw $this->createNotFoundException("Format not impl");
            //$serializer = $this->container->get('serializer');
            //return new Response($serializer->serialize(array('city' => $city, 'hours' => $hours ), $_format));
        }
        else
        {
            return $this->render('OipMszeBundle:Show:church.html.twig', array('church' => $church ));
        }
        
        
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
