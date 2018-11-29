<?php

namespace App\Repository;

use App\Entity\GithubData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GithubData|null find($id, $lockMode = null, $lockVersion = null)
 * @method GithubData|null findOneBy(array $criteria, array $orderBy = null)
 * @method GithubData[]    findAll()
 * @method GithubData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GithubDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GithubData::class);
    }
}
