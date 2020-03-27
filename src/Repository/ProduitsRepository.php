<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    /**
    * @return Produits[]
     */


    public function findByFourPres($four,$prestation):array
    {
      /* $connexion=$this->getEntityManager()->getConnection();
       $query=
           'SELECT * FROM produits WHERE fournisseur_id=:four';
        $smst=$connexion->prepare($query);
        $smst->execute(['four'=>$four]);

        return $smst->fetchAll();*/
        return $this->createQueryBuilder('produit')
            ->innerJoin('produit.fournisseur','fournisseur')
            ->innerJoin('produit.typePrestation','prestation')
            ->where('fournisseur.id = :fournisseurId')
            ->andWhere('prestation.id = :prestationId')
            ->setParameters([
                'fournisseurId'=>$four,
                'prestationId'=>$prestation
            ])
            ->getQuery()
            ->getResult();

    }





    public function findByPFP($prestation,$four,$pays):array
    {
       return $this->createQueryBuilder('produit')
           ->innerJoin('produit.fournisseur','fournisseur')
           ->innerJoin('produit.typePrestation','prestation')
           ->innerJoin('produit.pays','pays')
           ->where('fournisseur.id = :fournisseurId')
           ->andWhere('prestation.id = :prestationId')
           ->andWhere('pays.id = :paysId')
           ->setParameters([
               'fournisseurId'=>$four,
               'prestationId'=>$prestation,
               'paysId'=>$pays
           ])
           ->getQuery()
           ->getResult();


    }
    public function findByPays($pays):array
    {
        $connexion=$this->getEntityManager()->getConnection();
        $query=
            'SELECT * FROM produits WHERE pays_i=:pays';
        $smst=$connexion->prepare($query);
        $smst->execute(['pays'=>$pays]);

        return $smst->fetchAll();
    }



    public function findByProduitByFournisseur( $value)
    {

        return $this->createQueryBuilder('p')
            ->andWhere('fournisseur_id = :val')

            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Produits
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
