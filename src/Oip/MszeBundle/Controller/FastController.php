<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FastController extends Controller
{
    public function cityAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OipMszeBundle:City')->find($id);

        $xx = sizeof($entity);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find City entity.');
        }

        $serializer = $this->container->get('serializer');
        if ($entity != null)
        {
            return new Response($serializer->serialize($entity, 'json'));
        }
        return new Response($serializer->serialize(array( id => -1, districts => array()), 'json'));
    }   
}
