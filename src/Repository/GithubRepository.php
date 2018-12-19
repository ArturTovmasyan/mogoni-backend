<?php

namespace App\Repository;

use App\Entity\Github;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Github|null find($id, $lockMode = null, $lockVersion = null)
 * @method Github|null findOneBy(array $criteria, array $orderBy = null)
 * @method Github[]    findAll()
 * @method Github[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GithubRepository extends ServiceEntityRepository
{
    /**
     * GithubRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Github::class);
    }
}
