<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Repository;

use ArrayObject;
use Doctrine\ORM\ORMException;
use Exception;
use Rvadym\CriteriaBuilder\CountTrait;
use Rvadym\CriteriaBuilder\CriteriaBuilderTrait;
use Rvadym\Paginator\Model\PageQuery;
use ToDoTest\Adapters\Doctrine\AbstractRepository;
use ToDoTest\Adapters\Doctrine\Factory\TaskFactory;
use ToDoTest\Application\Collection\TaskCollection;
use ToDoTest\Application\Repository\Read\GetPaginatedTasksRepositoryInterface;
use ToDoTest\Application\Repository\Read\GetTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\CreateTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\DeleteTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Adapters\Doctrine\Entity\Task as TaskEntity;


class TaskDoctrineRepository extends AbstractRepository implements
    GetTaskRepositoryInterface,
    CreateTaskRepositoryInterface,
    UpdateTaskRepositoryInterface,
    GetPaginatedTasksRepositoryInterface,
    DeleteTaskRepositoryInterface
{
    use CriteriaBuilderTrait;
    use CountTrait;

    public const ALIAS = '_t';

    /**
     * @throws ORMException
     */
    public function create(Task $task): void
    {
        $taskEntity = new TaskEntity();

        $taskEntity->setId($task->getTaskId()->getValue());
        $taskEntity->setStatus($task->getTaskStatus()->getValue()->getValue());
        $taskEntity->setDescription($task->getTaskDescription()->getValue());
        $taskEntity->setIsDeleted(false);

        $this->getEm()->persist($taskEntity);
    }

    /**
     * @throws ORMException
     */
    public function delete(TaskId $taskId): void
    {
        $taskEntity = $this->getBy([
            'id' => $taskId->getValue(),
            'isDeleted' => false,
        ]);

        if (is_null($taskEntity)) {
            return;
        }

        $taskEntity->setIsDeleted(true);

        $this->getEm()->persist($taskEntity);
    }

    public function getOne(TaskId $taskId): ?Task
    {
        $taskEntity = $this->getBy([
            'id' => $taskId->getValue(),
            'isDeleted' => false,
        ]);

        if (is_null($taskEntity)) {
            return null;
        }

        return TaskFactory::createFromEntity($taskEntity);
    }

    /**
     * @throws ORMException
     */
    public function update(Task $task): void
    {
        $taskEntity = $this->getBy([
            'id' => $task->getTaskId()->getValue(),
            'isDeleted' => false,
        ]);

        if (is_null($taskEntity)) {
            return;
        }

        $taskEntity->setStatus($task->getTaskStatus()->getValue()->getValue());
        $taskEntity->setDescription($task->getTaskDescription()->getValue());

        $this->getEm()->persist($taskEntity);
    }

    /**
     * @throws Exception
     */
    public function getPaginated(PageQuery $page): TaskCollection
    {
        $queryBuilder = $this->getQueryBuilder(
            new ArrayObject($page->getCriteria()->getValue()),
            $this->getEm()->createQueryBuilder(),
            self::ALIAS
        );

        $query = $queryBuilder
            ->select(self::ALIAS)
            ->from(TaskEntity::class, self::ALIAS)
            ->andWhere(self::ALIAS . '.isDeleted = 0')
            ->setFirstResult($page->getOffset()->getValue())
            ->setMaxResults($page->getLimit()->getValue())
            ->getQuery();

        /** @var TaskEntity[] $taskCollection */
        $taskCollection = $query->getResult();

        $arr = [];

        foreach ($taskCollection as $taskEntity) {
            $arr[] = TaskFactory::createFromEntity($taskEntity);
        }

        return new TaskCollection(...$arr);
    }

    private function getBy(array $conditions): ?TaskEntity
    {
        /** @var TaskEntity $taskEntity */
        $taskEntity = $this->getEm()
            ->getRepository(TaskEntity::class)
            ->findOneBy($conditions);

        return $taskEntity;
    }
}
