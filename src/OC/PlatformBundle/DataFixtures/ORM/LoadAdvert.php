<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadSkill.php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;

class LoadAdvert implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Création de l'entité Image
    $image = new Image();
    $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
    $image->setAlt('Job de rêve');
    
    // Création d'une première candidature
    $application1 = new Application();
    $application1->setAuthor('Marine');
    $application1->setContent("J'ai toutes les qualités requises.");

    // Création d'une deuxième candidature par exemple
    $application2 = new Application();
    $application2->setAuthor('Pierre');
    $application2->setContent("Je suis très motivé.");

    $advert1 = new Advert();
    $advert1->setTitle('Recherche développeur Symfony');
    $advert1->setAuthor('Alexandre');
    $advert1->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
    
    // On lie les candidatures à l'annonce
    $application1->setAdvert($advert1);
    $application2->setAdvert($advert1);
    
    // On lie l'image à l'annonce
    $advert1->setImage($image);
    
    // On récupère toutes les compétences possibles
    $listSkills = $manager->getRepository('OCPlatformBundle:Skill')->findAll();

    // Pour chaque compétence
    foreach ($listSkills as $skill) {
      // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
      $advertSkill = new AdvertSkill();

      // On la lie à l'annonce, qui est ici toujours la même
      $advertSkill->setAdvert($advert1);
      // On la lie à la compétence, qui change ici dans la boucle foreach
      $advertSkill->setSkill($skill);

      // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
      $advertSkill->setLevel('Expert');

      // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
      $manager->persist($advertSkill);
    }
    
    $manager->persist($advert1);
    
    $manager->persist($application1);
    $manager->persist($application2);
    
    $advert2 = new Advert();
    $advert2->setTitle('Recherche développeur J2EE');
    $advert2->setAuthor('Alex');
    $advert2->setContent("Nous recherchons un développeur J2EE débutant sur Paris.");
    $advert2->setDate(new \Datetime(date('Y').'-01-01'));
    
    $manager->persist($advert2);
    
    $advert3 = new Advert();
    $advert3->setTitle('Recherche développeur PHP');
    $advert3->setAuthor('Rolex');
    $advert3->setContent("Nous recherchons un développeur PHP sur Paris.");
    $advert3->setDate(new \Datetime(date('Y').'-01-10'));
    
    $manager->persist($advert3);
    
    $application3 = new Application();
    $application3->setAuthor('Pierrot');
    $application3->setContent("Je suis très motivé.");
    $application3->setAdvert($advert3);
    
    $manager->persist($application3);
    
    $advert4 = new Advert();
    $advert4->setTitle('Recherche développeur C++');
    $advert4->setAuthor('EDF');
    $advert4->setContent("Nous recherchons un développeur C++ sur Marseille.");
    
    $manager->persist($advert4);
    
    $advert5 = new Advert();
    $advert5->setTitle('Recherche développeur PHP');
    $advert5->setAuthor('Maria');
    $advert5->setContent("Nous recherchons un développeur PHP sur Marseille.");
    $advert5->setDate(new \Datetime(date('Y').'-01-20'));
    
    $manager->persist($advert5);
    
    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();
  }
}