<?php

namespace Oip\MszeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MobileController extends Controller
{
    public function versionAction() {
        $vrepo = $this->getDoctrine()->getRepository('OipMszeBundle:Version');
        $v = $vrepo->getLast();
        if ($v == null)
        {
            $vout = 1;
        }
        else
        {
            $vout = $v->getId();
        }
        $serializer = $this->container->get('serializer');
        return new Response($serializer->serialize(array( 'v' => $vout), 'json'));
    }   
    
    public function countAction() {
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:Mass');
        $cnt = $repo->countAll();
        $serializer = $this->container->get('serializer');
        return new Response($serializer->serialize(array( 'c' => $cnt), 'json'));
    } 
    
     public function updateAction($from, $to, $_format, $_version = 9) {
     
         
         //        $logger = $this->get('logger');
       
        $data = array();
        
        $repo = $this->getDoctrine()->getRepository('OipMszeBundle:City');
        $all_cities = $repo->getAll();
        
        //   { 'd': [ [id, name, 
        //                  [cid, name, address, desc, lat, lng, 
        //                      [mid, start, details, m,t,w,t,f,s,s, mid, start, ...],
        //                   cid, name, address, desc, lat, lng, 
        //                      [mid, start, details, m,t,w,t,f,s,s, mid, start, ...],
        //                   ...
        //                  ]
        //             ], ... }
        
        $def_dist_id = -1;
        $total_cnt = 0;
        $next_church_id = 1;
        //var_dump($all_cities);
        for ($x = $from; $x < sizeof($all_cities) && ($to > 0 ? $x < $to : true); $x++, $total_cnt++)
        {
            $id = intval(($all_cities[$x]['id'] << 18) | $all_cities[$x]['did']);
            $city_name = '';
            
            if ($all_cities[$x]['dname'] == '')
            {
                $def_dist_id = $id;
                $city_name = $all_cities[$x]['name'];
                if (!isset($data[$id]))
                {
                    $data[$id] = array();
                    array_push($data[$id], $city_name);
                    array_push($data[$id], array());
                }
                
                if (isset($all_cities[$x]['cid']))
                {
                    $cid = $all_cities[$x]['cid'];//$next_church_id++;
                    if (!isset($data[$id][1][$cid])) {
                        $data[$id][1][$cid] = array();
                    
                        array_push($data[$id][1][$cid], $all_cities[$x]['cname']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['caddress']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['cdesc']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['clat']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['clgt']);
                        array_push($data[$id][1][$cid], 1);
                        array_push($data[$id][1][$cid], array()); //6
                        if ($_version > 9) {
                            array_push($data[$id][1][$cid], $all_cities[$x]['cwww']);
                        }
                        
                    }
                    if (isset($all_cities[$x]['mst'])) {                        
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['mst']));
                        array_push($data[$id][1][$cid][6], $all_cities[$x]['mdetails']);                 
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_mon']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_tue']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_wed']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_thu']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_fri']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_sat']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_sun']));
                    }
                }
            }
            else
            {
                $city_name = $all_cities[$x]['name'] . ' ' . $all_cities[$x]['dname'];
                if (!isset($data[$id]))
                {
                    $data[$id] = array();
                    array_push($data[$id], $city_name);
                    array_push($data[$id], array());
                }
                if (isset($all_cities[$x]['cid']))
                {
                    $cid = $all_cities[$x]['cid'];
                    if (!isset($data[$id][1][$cid])) {
                        $data[$id][1][$cid] = array();
                    
                        array_push($data[$id][1][$cid], $all_cities[$x]['cname']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['caddress']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['cdesc']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['clat']);
                        array_push($data[$id][1][$cid], $all_cities[$x]['clgt']);
                        array_push($data[$id][1][$cid], 1);
                        array_push($data[$id][1][$cid], array()); //6
                        if ($_version > 9) {
                            array_push($data[$id][1][$cid], $all_cities[$x]['cwww']);
                        }
                    }
                    if (isset($all_cities[$x]['mst'])) {                        
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['mst']));
                        array_push($data[$id][1][$cid][6], $all_cities[$x]['mdetails']);
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_mon']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_tue']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_wed']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_thu']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_fri']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_sat']));
                        array_push($data[$id][1][$cid][6], intval($all_cities[$x]['day_sun']));
                    }
                }
                
                //add also to default
                if (isset($all_cities[$x]['cid']))
                {
                    $cid = $all_cities[$x]['cid'];
                    if (!isset($data[$def_dist_id][1][$cid])) {
                        $data[$def_dist_id][1][$cid] = array();
                        //var_dump($def_dist_id);
                        array_push($data[$def_dist_id][1][$cid], $all_cities[$x]['cname']);
                        array_push($data[$def_dist_id][1][$cid], $all_cities[$x]['caddress']);
                        array_push($data[$def_dist_id][1][$cid], $all_cities[$x]['cdesc']);
                        array_push($data[$def_dist_id][1][$cid], $all_cities[$x]['clat']);
                        array_push($data[$def_dist_id][1][$cid], $all_cities[$x]['clgt']);
                        array_push($data[$def_dist_id][1][$cid], 0);
                        array_push($data[$def_dist_id][1][$cid], array()); //6
                        if ($_version > 9) {
                            array_push($data[$def_dist_id][1][$cid], $all_cities[$x]['cwww']);
                        }
                    }
                    if (isset($all_cities[$x]['mst'])) {                        
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['mst']));
                        array_push($data[$def_dist_id][1][$cid][6], $all_cities[$x]['mdetails']);
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['day_mon']));
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['day_tue']));
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['day_wed']));
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['day_thu']));
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['day_fri']));
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['day_sat']));
                        array_push($data[$def_dist_id][1][$cid][6], intval($all_cities[$x]['day_sun']));
                    }
                }
            }
        }
       
        if ($_format == 'gz')
        {
            return new Response(gzencode(json_encode($data), 9));
        }
        else
        {
            return new Response(json_encode($data));
        }
         
     }

}
