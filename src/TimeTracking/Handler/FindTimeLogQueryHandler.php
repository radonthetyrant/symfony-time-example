<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Project;
use App\TimeTracking\Query\FindProjectQuery;
use App\TimeTracking\Query\FindTimeLogQuery;
use App\TimeTracking\TimeLog;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

class FindTimeLogQueryHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    )
    {
    }

    public function __invoke(FindTimeLogQuery $query): Collection
    {
        $criteria = $this->security->getUser() !== null ? ['user' => $this->security->getUser()] : [];

        if ($query->project !== null) {
            $criteria['project'] = $query->project;
        }

        $result = $this->entityManager->getRepository(TimeLog::class)->findBy(
            $criteria,
            limit: $query->perPage,
            offset: max(0, $query->page - 1) * $query->perPage,
        );

        return new ArrayCollection($result);
    }

}