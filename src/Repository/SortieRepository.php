<?php

namespace App\Repository;

use App\DTO\FiltersDTO;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function findWithSearchFilters(FiltersDTO $filters, UserInterface $user)
    {
        $qb = $this->createQueryBuilder('s');
        if (isset($filters->search)) {
            if ($filters->search) {
                $qb->andWhere('s.nom LIKE :search')->setParameter('search', '%' . $filters->search . '%');
            }
        }
        if ($filters->organisateurFilter) {
            $qb->andWhere('s.organisateur = :organisateur')->setParameter('organisateur', true);
        }
        if (isset($filters->sites) && $filters->sites->getId()) {
            $qb->andWhere('s.site = :site')->setParameter('site', $filters->sites->getId());
        }
        if (isset($filters->etat) && $filters->etat->value) {
            $qb->andWhere('s.etat LIKE :etat')->setParameter('etat', '%' . $filters->etat->value . '%');
        }
        if ($filters->passeFilter) {
            $qb->andWhere('s.etat LIKE :passe')->setParameter('passe', "Passée");
        }
        if ($filters->inscritFilter) {
            $qb->andWhere(':user1 MEMBER OF s.inscriptions')->setParameter('user1', $user);
        }
        if ($filters->pasInscritFilter) {
            $qb->andWhere(':user2 NOT MEMBER OF s.inscriptions')->setParameter('user2', $user);
        }

        if ($filters->dateDebut && $filters->dateFin) {
            $qb->andWhere(':start_date < s.dateDebut')
                ->andWhere('s.dateDebut < :end_date')
                ->setParameters([
                    'start_date' => $filters->dateDebut->format("Y-m-d H:i:s"),
                    'end_date' => $filters->dateFin->format("Y-m-d H:i:s")]);
        }
        if ($filters->dateDebut && !$filters->dateFin) {
            $qb->andWhere('s.dateDebut > :date_debut')->setParameter('date_debut', $filters->dateDebut->format("Y-m-d H:i:s"));
        }
        if ($filters->dateFin && !$filters->dateDebut) {
            $qb->andWhere('DATE_ADD(s.dateDebut, s.duree, \'minute\') < :date_fin')->setParameter('date_fin', $filters->dateFin->format("Y-m-d H:i:s"));
        }

        return $qb->getQuery()->getResult();
    }
}
