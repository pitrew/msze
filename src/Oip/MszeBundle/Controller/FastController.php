<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FastController extends Controller
{
    public function cityAction($id) {
        $em = $this->getDoctrine()->getManager();
        $crepo = $em->getRepository('OipMszeBundle:City');
        $drepo = $em->getRepository('OipMszeBundle:District');
        $entity = $crepo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find City entity.');
        }
        $districts = $entity->getDistricts();
        
        $dresult = array();
        for ($x = 0; $x < sizeof($districts); $x++)
        {
            $dresult[$x] = array('id' => $districts[$x]->getId(), 'name' => $districts[$x]->getName());
        }
        //$defDist = $drepo->findDefaultDistrict($id);
        //array_unshift($dresult, array('id' => $defDist->getId(), 'name' => 'Brak dzielnicy'));
        
        $result = array(
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'districts' => $dresult);

        $serializer = $this->container->get('serializer');
        if ($entity != null)
        {
            return new Response($serializer->serialize($result, 'json'));
        }
        return new Response($serializer->serialize(array( id => -1, districts => array()), 'json'));
    }   
    
    public function districtAction($id) {
        $serializer = $this->container->get('serializer');
        $em = $this->getDoctrine()->getManager();
        
        $crepo = $em->getRepository('OipMszeBundle:City');
        
        $entity = $em->getRepository('OipMszeBundle:District')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find District entity.');
        }
        
        if ($entity->isDefault()) {
            $churches = $crepo->findAllChurches($entity->getCity()->getId());
        } else {
            $churches = $entity->getChurches();    
        }
        
        
        $dresult = array();
        for ($x = 0; $x < sizeof($churches); $x++)
        {
            $dresult[$x] = array('id' => $churches[$x]->getId(), 'name' => $churches[$x]->getName());
        }
        $result = array(
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'churches' => $dresult);

        if ($entity != null)
        {
            return new Response($serializer->serialize($result, 'json'));
        }
        return new Response($serializer->serialize(array( id => -1, churches => array()), 'json'));
    }
    
    public function churchAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OipMszeBundle:Church')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Church entity.');
        }
        
        $masses = $entity->getMasses();
        $dresult = array();
        for ($x = 0; $x < sizeof($masses); $x++)
        {
            $dresult[$x] = array(
                'id' => $masses[$x]->getId(), 
                'start_time' => $masses[$x]->getStartTime(),
                'details' => $masses[$x]->getDetails(),
                'day_mon' => $masses[$x]->getDayMon(),
                'day_tue' => $masses[$x]->getDayTue(),
                'day_wed' => $masses[$x]->getDayWed(),
                'day_thu' => $masses[$x]->getDayThu(),
                'day_fri' => $masses[$x]->getDayFri(),
                'day_sat' => $masses[$x]->getDaySat(),
                'day_sun' => $masses[$x]->getDaySun()
                );
        }
        $result = array(
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'address' => $entity->getAddress(),
            'description' => $entity->getDescription(),
            'masses' => $dresult);

        $serializer = $this->container->get('serializer');
        if ($entity != null)
        {
            return new Response($serializer->serialize($result, 'json'));
        }
        return new Response($serializer->serialize(array( id => -1, masses => array()), 'json'));
    }
}
