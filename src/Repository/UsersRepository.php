<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }
    
    
    public function findById($id, $limit)
    {
        $result = $this->createQueryBuilder('users')
            ->andWhere('users.id >= :id')
            ->setParameter('id', $id)
            ->orderBy('users.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
        
        return $result;
    }
    
    
    public function findByText($searchtext, $id, $limit)
    {
        
        $params = [
            'id' => (int)$id,
            'searchtext' => '*'.$searchtext.'*',
            'limit' => (int)$limit
        ];
        $sql = 'SELECT `id`, `firstname`, `secondname`, `surname` FROM `users` WHERE `id`>=:id AND MATCH(`firstname`, `secondname`, `surname`) AGAINST(:searchtext IN BOOLEAN MODE) LIMIT :limit';
    
        $rsm            = new Query\ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addEntityResult(Users::class, "users");
        $rsm->addFieldResult('users','id','id');
        $rsm->addFieldResult('users','firstname','firstname');
        $rsm->addFieldResult('users','secondname','secondname');
        $rsm->addFieldResult('users','surname','surname');
    
        $result = $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameters($params)
            ->getArrayResult();
        
        return $result;
    }
    
}
