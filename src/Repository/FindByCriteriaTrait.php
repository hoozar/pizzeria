<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

trait FindByCriteriaTrait
{
    abstract public function createQueryBuilder(string $alias, string|null $indexBy = null): QueryBuilder;

    public function findByCriteria(array $criteria, $limit = null): array
    {
        $qb = $this->createQueryBuilder('s');
        foreach ($criteria as $key => $value) {
            if (!empty($value)) {
                $qb->andWhere("s.$key = :$key")->setParameter($key, $value);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
