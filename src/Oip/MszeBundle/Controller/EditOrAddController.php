<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
