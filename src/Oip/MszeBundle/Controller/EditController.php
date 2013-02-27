<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Oip\MszeBundle\Entity\City;
use Oip\MszeBundle\Form\CityType;

class EditController extends Controller
{
    public function cityAction($id)
    {
        $url = null;
        $em = $this->getDoctrine()->getManager();
        
        if ($id == -1)
        {
            $city = new City();
            $form = $this->createForm(new CityType(), $city);
        }
        else
        {
            $repo = $this->getDoctrine()->getRepository('OipMszeBundle:City');
            $city = $repo->findOneById($id);
            if ($city == null)
            {
                throw $this->createNotFoundException('City id ['.$id.'] not found');
            }
            $form = $this->createForm(new CityType(), $city);
        }
        
        if ($this->getRequest()->isMethod('POST'))
        {
            $form->bindRequest($this->getRequest());
            if ($form->isValid())
            {
                if ($id == -1)
                {
                    $em->persist($city);   
                }
                $em->flush();
                return $this->redirect($this->generateUrl('show_cities'));
            }
        }
        else
        {   
            $url = $this->generateUrl('edit_city', array('id' => $id));
        }
        
        return $this->render('OipMszeBundle:Edit:city.html.twig', array('form' => $form->createView(), 'url' => $url ));
    }
}
