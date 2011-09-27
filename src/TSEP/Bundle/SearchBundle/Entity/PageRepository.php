<?php
namespace TSEP\Bundle\SearchBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    public function findBySearchQuery($query, $profile = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('page')
           ->from('TSEP\Bundle\SearchBundle\Entity\Page', 'page')
           ->where($qb->expr()->orx(
               $qb->expr()->like('page.text', ':text')
           ))
           ->setParameter('text', $query);

        return $qb->getQuery()->getResult();
    }
}