<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EditOrAddController extends Controller
{
    public function indexAction($city_id, $district_id, $church_id)
    {
        return $this->render('OipMszeBundle:EditOrAdd:index.html.twig', 
                  array(
                      'city_id' => $city_id, 
                      'district_id' => $district_id,
                      'church_id' => $church_id
                  ));
    }
    
    //only post
    public function saveAction()
    {
        $data = $this->getRequest()->request->get('all_data');
        $serializer = $this->container->get('serializer');
        return new Response($serializer->serialize($data, 'json'));
    }
}
