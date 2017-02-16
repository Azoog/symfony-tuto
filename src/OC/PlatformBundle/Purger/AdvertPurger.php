<?php

namespace OC\PlatformBundle\Purger;

class AdvertPurger
{
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManagerInterface $entity) {
        $this->em = $entity;
    }
    
    public function purger($days) {
        //on récupère les annonces
        $listAdverts = $this->em->getRepository('OCPlatformBundle:Advert')->getAdvertsXDaysAgo($days);
        
        
        foreach ($listAdverts as $advert) {
            //on récupère les skills liés aux annonces
            $listAdvertSkills = $this->em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert' => $listAdverts));
            
            foreach ($listAdvertSkills as $advertSkill) {
                $advert->removeSkill($advertSkill);
                
                $this->em->remove($advertSkill);
            }
            
            $this->em->remove($advert);
            
        }
        
        $this->em->flush();
               
    }
}

