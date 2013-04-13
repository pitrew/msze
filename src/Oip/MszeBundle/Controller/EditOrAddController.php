<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class EditOrAddController extends Controller
{
    private $skipCaptcha = true;
    
    public function indexAction($city_id, $district_id, $church_id)
    {
        $cap = new \Oip\MszeBundle\OipCaptcha();
        $form = $cap->recaptcha_get_html('6Lehmd0SAAAAAArc4LsrgolSi8b9O8rLt_OEnHxh');
        
        return $this->render('OipMszeBundle:EditOrAdd:index.html.twig', 
                  array(
                      'city_id' => $city_id, 
                      'district_id' => $district_id,
                      'church_id' => $church_id,
                      'form' => $form
                  ));
    }
    
    private function getArrayElement($key, $data, $default = null)
    {
        $ret = $default;
        if (array_key_exists($key, $data)) {
            $ret = $data[$key];
        } else {
            $ret = $default;
        }
        return $ret;
    }
    
    //only post
    public function saveAction()
    {
        $serializer = $this->container->get('serializer');
        $data_ret = array();
         
        if (!$this->skipCaptcha)
        {
            if (!$this->getRequest()->request->has('re_c') || 
                !$this->getRequest()->request->has('re_r'))
            {    
                $data_ret['error'] = "Wpisz kod";
                return new Response($serializer->serialize($data_ret, 'json'));
            }

            $re_c = $this->getRequest()->request->get('re_c');
            $re_r = $this->getRequest()->request->get('re_r');

            $cap = new \Oip\MszeBundle\OipCaptcha();
            $cap_resp = $cap->recaptcha_check_answer("6Lehmd0SAAAAAMCJasaKSOzSB_ZQkEfLFgpXQ4tO", 
                    $this->getRequest()->getClientIp(), $re_c, $re_r);

            if (!$cap_resp->is_valid) {
                   $data_ret['error'] = "Kod nieprawidłowy";
                   return new Response($serializer->serialize($data_ret, 'json'));
            }
        }
        
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
        
        $caddrress = $this->getArrayElement('caddr', $data, '');
        $cdetails = $this->getArrayElement('cdesc', $data, '');
        
        $clat = $this->getArrayElement('clat', $data);
        $clng = $this->getArrayElement('clng', $data);
        
        $mmod = $this->getArrayElement('mmod', $data);
        $mdel = $this->getArrayElement('mdel', $data);
               
        $crepo = $this->getDoctrine()->getRepository('OipMszeBundle:City');
        if ($city_id != -1) {
            $city = $crepo->find($city_id);
            if ($city == null) {
                return new Response($serializer->serialize(array('error' => '0001.Nie ma takiego miasta!'), 'json'));
            }
            $city->setName($cname);
            $em->flush();
        } else {
            if ($cname == null) {
                return new Response($serializer->serialize(array('error' => '0002.Podaj nazwę miasta!'), 'json'));
            }
            
            $city = new \Oip\MszeBundle\Entity\City();
            $city->setName($cname);
            $em->persist($city);
            $em->flush();

            $defDist = new \Oip\MszeBundle\Entity\District();
            $defDist->setName('');
            $defDist->setCity($city);
            $em->persist($defDist);
            $em->flush();
            
        }
        
        $drepo = $this->getDoctrine()->getRepository('OipMszeBundle:District');
        if ($district_id != -1) {
            $district = $drepo->findOrDef($city->getId(), $district_id);
            if ($district == null) {
                return new Response($serializer->serialize(array('error' => '0003.Nie ma takiej dzielnicy!'), 'json'));
            }
            if ($dname == '' && $district->getName() != '') {
                return new Response($serializer->serialize(array('error' => '0004.Zła nazwa dzielnicy!'), 'json'));
            }
            $district->setName($dname);
            $em->flush();
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
            $church->setName($chname);
            $church->setAddress($caddrress);
            $church->setDescription($cdetails);
            $church->setLatitude($clat);
            $church->setLongitude($clng);
            $em->flush();
        } else {
            if ($chname != null && $district != null) {
                //return new Response($serializer->serialize(array('error' => '0006.Podaj nazwę kościoła!'), 'json'));
            
                $church = new \Oip\MszeBundle\Entity\Church();
                $church->setName($chname);
                $church->setDistrict($district);

                $church->setAddress($caddrress);
                $church->setDescription($cdetails);
                $church->setFoto('');
                
                $church->setLatitude($clat);
                $church->setLongitude($clng);

                $em->persist($church);
                $em->flush();
            }
        }
        
        $mrepo = $this->getDoctrine()->getRepository('OipMszeBundle:Mass');
        if ($mdel != null && $church != null) {
            foreach($mdel as $mid => $mpar)
            {
                $mass = $mrepo->find($mid);
                if ($mass != null)
                {
                    $em->remove($mass);
                    $em->flush();
                }
            }
        }
        if ($mmod != null && $church != null) {
            $dt = new \DateTime();
            foreach($mmod as $mid => $mpar)
            {
                if ($mid < 0)
                {
                    $mass = new \Oip\MszeBundle\Entity\Mass();
                    $mass->setChurch($church);
                    
                    $dt->setTime($mpar['hours'], $mpar['minutes'], 0);
                    $mass->setStartTime($dt);
                    $mass->setDetails($mpar['details']);
                    $mass->setDayMon(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_mon']));
                    $mass->setDayTue(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_tue']));
                    $mass->setDayWed(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_wed']));
                    $mass->setDayThu(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_thu']));
                    $mass->setDayFri(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_fri']));
                    $mass->setDaySat(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_sat']));
                    $mass->setDaySun(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_sun']));
                    $em->persist($mass);
                    $em->flush();
                }
                else
                {
                    $mass = $mrepo->find($mid);
                    if ($mass != null)
                    {
                        $dt->setTime($mpar['hours'], $mpar['minutes'], 0);
                        $mass->setStartTime($dt);
                        $mass->setDetails($mpar['details']);
                        $mass->setDayMon(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_mon']));
                        $mass->setDayTue(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_tue']));
                        $mass->setDayWed(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_wed']));
                        $mass->setDayThu(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_thu']));
                        $mass->setDayFri(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_fri']));
                        $mass->setDaySat(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_sat']));
                        $mass->setDaySun(\Oip\MszeBundle\OipHelpers::stringToBool($mpar['day_sun']));
                        $em->flush();
                    }
                }
            }
        }
        
        $data_ret['city_id'] = ($city != null?$city->getId():-1);
        $data_ret['district_id'] = ($district != null?$district->getId():-1);
        $data_ret['church_id'] = ($church != null?$church->getId():-1);
        
        return new Response($serializer->serialize($data_ret, 'json'));
    }
}
