<?php

namespace App\Repository;

use App\Entity\PublishProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PublishProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublishProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublishProduct[]    findAll()
 * @method PublishProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublishProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PublishProduct::class);
    }
}
