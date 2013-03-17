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
    
    private function getArrayElement($key, $data)
    {
        $ret = null;
        if (array_key_exists($key, $data)) {
            $ret = $data[$key];
        } else {
            $ret = null;
        }
        return $ret;
    }
    
    //only post
    public function saveAction()
    {
        $data = $this->getRequest()->request->get('all_data');
        $em = $this->getDoctrine()->getManager();
        
        $city = null;
        $district = null;
        $church = null;
        
        $city_id = $data['cid'];
        $district_id = $data['did'];
        $church_id = $data['chid'];
        
        $cname = $this->getArrayElement('cname', $data);
        $dname = $this->getArrayElement('dname', $data);
        $chname = $this->getArrayElement('chname', $data);
        
        $serializer = $this->container->get('serializer');
        
        $crepo = $this->getDoctrine()->getRepository('OipMszeBundle:City');
        if ($city_id != -1) {
            $city = $crepo->find($city_id);
            if ($city == null) {
                return new Response($serializer->serialize(array('error' => '0001.Nie ma takiego miasta!'), 'json'));
            }
        } else {
            if (!$cname == null) {
                //return new Response($serializer->serialize(array('error' => '0002.Podaj nazwę miasta!'), 'json'));
            
                $city = new \Oip\MszeBundle\Entity\City();
                $city->setName($cname);
                $em->persist($city);
                $em->flush();
            }
        }
        
        $drepo = $this->getDoctrine()->getRepository('OipMszeBundle:District');
        if ($district_id != -1) {
            $district = $drepo->find($district_id);
            if ($district == null) {
                return new Response($serializer->serialize(array('error' => '0003.Nie ma takiej dzielnicy!'), 'json'));
            }
        } else {
            if ($dname != null && $city != null) {
                //return new Response($serializer->serialize(array('error' => '0004.Podaj nazwę dzielnicy!'), 'json'));
            
                $district = new \Oip\MszeBundle\Entity\District();
                $district->setName($dname);
                $district->setCity($city);
                $em->persist($district);
                $em->flush();
            }
        }
        
        $chrepo = $this->getDoctrine()->getRepository('OipMszeBundle:Church');
        if ($church_id != -1) {
            $church = $chrepo->find($church_id);
            if ($church == null) {
                return new Response($serializer->serialize(array('error' => '0005.Nie ma takiego kościoła!'), 'json'));
            }
        } else {
            if ($chname != null && $district != null) {
                //return new Response($serializer->serialize(array('error' => '0006.Podaj nazwę kościoła!'), 'json'));
            
                $church = new \Oip\MszeBundle\Entity\Church();
                $church->setName($chname);
                $church->setDistrict($district);

                $church->setAddress('');
                $church->setDescription('');
                $church->setFoto('');

                $em->persist($church);
                $em->flush();
            }
        }
        
        $data_ret = array();
        $data_ret['city_id'] = ($city != null?$city->getId():-1);
        $data_ret['district_id'] = ($district != null?$district->getId():-1);
        $data_ret['church_id'] = ($church != null?$church->getId():-1);
        return new Response($serializer->serialize($data_ret, 'json'));
    }
}
