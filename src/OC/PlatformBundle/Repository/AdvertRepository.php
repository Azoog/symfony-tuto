<?php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAdverts($page, $nbPerPage)
    {
      $query = $this->createQueryBuilder('a')
        ->leftJoin('a.image', 'i')
        ->addSelect('i')
        ->leftJoin('a.categories', 'c')
        ->addSelect('c')
        ->leftJoin('a.skills','s')
        ->addSelect('s')
        ->orderBy('a.date', 'DESC')
        ->getQuery()
      ;

      $query
        // On définit l'annonce à partir de laquelle commencer la liste
        ->setFirstResult(($page-1) * $nbPerPage)
        // Ainsi que le nombre d'annonce à afficher sur une page
        ->setMaxResults($nbPerPage)
      ;

      // Enfin, on retourne l'objet Paginator correspondant à la requête construite
      // (n'oubliez pas le use correspondant en début de fichier)
      return new Paginator($query, true);
    }
  
    public function getAdvertWithCategories(array $categoryNames)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->innerJoin('a.categories', 'c')
            ->addSelect('c');
            
        // Puis on filtre sur le nom des catégories à l'aide d'un IN
        $qb->where($qb->expr()->in('c.name', $categoryNames));
        // La syntaxe du IN et d'autres expressions se trouve dans la documentation Doctrine
        
        return $qb
            ->getQuery()
            ->getResult()
        ;
        
    }
    
    public function myFind()
    {
      $qb = $this->createQueryBuilder('a');

      // On peut ajouter ce qu'on veut avant
      $qb
        ->where('a.author = :author')
        ->setParameter('author', 'Marine')
      ;

      // On applique notre condition sur le QueryBuilder
      $this->whereCurrentYear($qb);

      // On peut ajouter ce qu'on veut après
      $qb->orderBy('a.date', 'DESC');

      return $qb
        ->getQuery()
        ->getResult()
      ;
    }
    
    public function getAdvertWithApplications()
    {
      $qb = $this
        ->createQueryBuilder('a')
        ->leftJoin('a.applications', 'app')
        ->addSelect('app')
      ;

      return $qb
        ->getQuery()
        ->getResult()
      ;
    }

  public function whereCurrentYear(QueryBuilder $qb)
  {
    $qb
      ->andWhere('a.date BETWEEN :start AND :end')
      ->setParameter('start', new \Datetime(date('Y').'-01-01'))  // Date entre le 1er janvier de cette année
      ->setParameter('end',   new \Datetime(date('Y').'-12-31'))  // Et le 31 décembre de cette année
    ;
  }
}
