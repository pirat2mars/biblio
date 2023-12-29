<?php

namespace App\Repository;

use App\Entity\Livre;
use App\Entity\Emprunt;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
   public function findByTitre($value): array
   {
       return $this->createQueryBuilder('l')
           ->andWhere('l.titre LIKE :val')
           ->setParameter('val', "%$value%")
           ->orderBy('l.titre', 'ASC')           
           ->getQuery()
           ->getResult()
       ;
       // ->setMaxResults(10)
   }

   public function findLivresEmpruntes(): array
   {
       return $this->createQueryBuilder('l')
           ->join(Emprunt::class, "e", "WITH", "l.id = e.livre")
           ->where('e.dateRetour IS NULL')
           ->orderBy('l.titre', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//Methode create query de l'entitymanager pour ecrire des requestes DQL
// on utilise pas les tables mais les classes entités
// dans la requete imbriquée on ne peu pas reutiliser le meme alias pour une même classe

   public function findLivresDisponibles(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT l
            FROM App\Entity\Livre l
            WHERE l.id NOT IN
                (SELECT livre.id
                    FROM App\Entity\Emprunt e Join App\Entity\Livre livre WITH e.livre = livre.id
                    WHERE e.dateRetour IS NULL )
            ORDER BY l.titre
            "
        );
        return $query->getResult();

   }



//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
