<?php

namespace TSEP\Bundle\SearchBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SearchRepository extends EntityRepository
{
    public function findBySearchQuery($query, $profile = null)
    {
        return $searches;
    }
}
