<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Doctrine;

use Doctrine\ORM\EntityManager;

abstract class AbstractRepository
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function getEm(): EntityManager
    {
        return $this->em;
    }
}
