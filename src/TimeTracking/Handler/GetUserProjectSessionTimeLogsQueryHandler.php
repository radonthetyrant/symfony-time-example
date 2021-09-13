<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Query\GetUserProjectSessionTimeLogsQuery;
use App\TimeTracking\TimeLog;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetUserProjectSessionTimeLogsQueryHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(GetUserProjectSessionTimeLogsQuery $query): Collection
    {
        $criteria = ['user' => $query->userId, 'project' => $query->projectId];
        if ($query->isRunningOnly) {
            $criteria['endAt'] = null;
        }
        $result = $this->entityManager->getRepository(TimeLog::class)->findBy($criteria);
        return new ArrayCollection($result);
    }

}