<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

//façon DQL (plus de possibilitées)
  public function findAllAssocDql()
  {
    $em=$this->getEntityManager();
    $dql =
      'SELECT c.id,c.name FROM App\Entity\Category c ORDER BY c.name ASC';
      $query = $em->createQuery($dql);
      return $query->execute();//Renvoi un tableau  de tableaux associatifs
  }

  public function findAllAssoc(){
    $connection =$this->getEntityManager()->getConnection();
    $sql=
    'SELECT DISTINCT category.name, category.id
    FROM category
    JOIN question
    ON question.category_id = category.id';
    $query=$connection->prepare($sql);
    $query->execute();
    return $query->fetchAll();
  }

}
