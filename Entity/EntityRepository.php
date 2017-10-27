<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * This entity repository is implemented using the principles described here:
 * https://www.tomasvotruba.cz/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/
 *
 * @method array findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method null|object findOneBy(array $criteria)
 * @method array findAll()
 */
abstract class EntityRepository
{
    /**
     * @var DoctrineEntityRepository
     */
    protected $repository;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function __construct(ManagerRegistry $managerRegistry, PaginatorInterface $paginator, string $class) {
        $this->repository = $managerRegistry
            ->getManagerForClass($class)
            ->getRepository($class)
        ;

        $this->paginator = $paginator;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->repository, $name)) {
            return call_user_func_array([$this->repository, $name], $arguments);
        }
    }

    /**
     * @param int $page
     * @param int $itemsPerPage
     * @param array $orderBy
     * @return SiteSetting[]
     */
    public function findAllWithPaging($page = 1, $itemsPerPage = 100, array $orderBy = [])
    {
        $qb = $this->repository->createQueryBuilder('s');

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy($field, $direction);
        }

        /** @var SiteSetting[] $objs */
        $objs = $this->paginator->paginate(
            $qb,
            $page,
            $itemsPerPage
        );

        return $objs;
    }
}
