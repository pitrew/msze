<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Oip\MszeBundle\LocalEntity\Search;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //TODO: add autocomplete form
        

        
//        $city = new City();
//        $city->setName('test4');
//        $city->setDistrict('dist2');
//        
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($city);
//        $em->flush();
        
        //$search = new Search();
               
        //$form = $this->createForm(new SearchType(), $search);
        //$form = $this->createFormBuilder($search)
        //        ->add('pattern', 'text')
        //        ->getForm();
        
        return $this->render('OipMszeBundle:Default:index.html.twig');
    }
}
