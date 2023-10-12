<?php

namespace App\Repository;

use App\DTO\FiltersDTO;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findWithSearchFilters(FiltersDTO $filters)
    {
        $qb = $this->createQueryBuilder('s');
        if (isset($filters->search)) {
            if ($filters->search) {
                $qb->andWhere('s.nom LIKE :term')->setParameter('term', '%' . $filters->search . '%');
            }
        }
        if ($filters->organisateurFilter) {
            $qb->andWhere('s.organisateur = :organisateur')->setParameter('organisateur', true);
        }

        return $qb->getQuery()->getResult();
    }
}
