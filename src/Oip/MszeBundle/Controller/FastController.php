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
        
        $massesEntity = $em->getRepository('OipMszeBundle:Mass');
        $sundayMasses = $massesEntity->findMassesOrderByHour($id, true);
        $weekMasses = $massesEntity->findMassesOrderByHour($id, false);
        
        $sresult = array();
        $wresult = array();
        for ($x = 0; $x < sizeof($sundayMasses); $x++)
        {
            $sresult[$x] = array(
                'id' => $sundayMasses[$x]->getId(), 
                'start_time' => $sundayMasses[$x]->getStartTime(),
                'details' => $sundayMasses[$x]->getDetails()
                /*'day_mon' => $sundayMasses[$x]->getDayMon(),
                'day_tue' => $sundayMasses[$x]->getDayTue(),
                'day_wed' => $sundayMasses[$x]->getDayWed(),
                'day_thu' => $sundayMasses[$x]->getDayThu(),
                'day_fri' => $sundayMasses[$x]->getDayFri(),
                'day_sat' => $sundayMasses[$x]->getDaySat(),
                'day_sun' => $sundayMasses[$x]->getDaySun()*/
                );
        }
        
        for ($x = 0; $x < sizeof($weekMasses); $x++)
        {
            $wresult[$x] = array(
                'id' => $weekMasses[$x]->getId(), 
                'start_time' => $weekMasses[$x]->getStartTime(),
                'details' => $weekMasses[$x]->getDetails(),
                'day_mon' => $weekMasses[$x]->getDayMon(),
                'day_tue' => $weekMasses[$x]->getDayTue(),
                'day_wed' => $weekMasses[$x]->getDayWed(),
                'day_thu' => $weekMasses[$x]->getDayThu(),
                'day_fri' => $weekMasses[$x]->getDayFri(),
                'day_sat' => $weekMasses[$x]->getDaySat(),
                'day_sun' => $weekMasses[$x]->getDaySun()
                );
        }
        $result = array(
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'address' => $entity->getAddress(),
            'description' => $entity->getDescription(),
            'latitude' => $entity->getLatitude(),
            'longitude' => $entity->getLongitude(),
            'smasses' => $sresult,
            'wmasses' => $wresult);

        $serializer = $this->container->get('serializer');
        if ($entity != null)
        {
            return new Response($serializer->serialize($result, 'json'));
        }
        return new Response($serializer->serialize(array( id => -1, masses => array()), 'json'));
    }
    
    public function churchMassesAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('OipMszeBundle:Church');

        //$days = array('Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
        $hours = array();
        $hours[0] = $repo->findAllHours($id, 'sun');
        $hours[1] = $repo->findAllHours($id, 'mon');
        $hours[2] = $repo->findAllHours($id, 'tue');
        $hours[3] = $repo->findAllHours($id, 'wed');
        $hours[4] = $repo->findAllHours($id, 'thu');
        $hours[5] = $repo->findAllHours($id, 'fri');
        $hours[6] = $repo->findAllHours($id, 'sat');
        
        $serializer = $this->container->get('serializer');
        return new Response($serializer->serialize(array('hours' => $hours ), 'json'));
    }
}
