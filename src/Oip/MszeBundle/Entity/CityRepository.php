<?php

namespace Oip\MszeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CityRepository extends EntityRepository
{
    public function findByAny($pattern)
    {
        $pattern = str_replace('\\', '\\\\\\', $pattern);
        $pattern = str_replace('_', '\\_', $pattern);
        $pattern = str_replace('%', '\\%', $pattern);
        
        return $this->getEntityManager()
                ->createQuery("select c from OipMszeBundle:City c where c.name like :pattern or c.slug like :pattern")
                ->setParameter('pattern', '%'.$pattern.'%')
                ->getResult();
    }
    
    public function findAllHours($city_id, $day)
    {
        $str = '1=1 ';
        switch ($day)
        {
            case 'mon':
                $str = 'm.day_mon = 1';
                break;
            case 'tue':
                $str = 'm.day_tue = 1';
                break;
            case 'wed':
                $str = 'm.day_wed = 1';
                break;
            case 'thu':
                $str = 'm.day_thu = 1';
                break;
            case 'fri':
                $str = 'm.day_fri = 1';
                break;
            case 'sat':
                $str = 'm.day_sat = 1';
                break;
            case 'sun':
                $str = 'm.day_sun = 1';
                break;
        }
        
        return $this->getEntityManager()
                ->createQuery('select m.start_time from OipMszeBundle:Mass m, OipMszeBundle:Church c where m.church = c.id and c.city = :city_id '
                        . ' and (' .
                        $str
                        . ') group by m.start_time')
                        //m.day_mon = :d_mon and m.day_tue = :d_tue and m.day_wed = :d_wed '
                        //. ' and m.day_thu = :d_thu and m.day_fri = :d_fri and m.day_sat = :d_sat and m.day_sun = :d_sun)'
                        //. ' group by m.start_time')
                ->setParameters(array('city_id' => $city_id))
                ->getResult();
    }
}
