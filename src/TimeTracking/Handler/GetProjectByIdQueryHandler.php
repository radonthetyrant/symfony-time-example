<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Project;
use App\TimeTracking\Query\FindProjectQuery;
use App\TimeTracking\Query\GetProjectByIdQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetProjectByIdQueryHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(GetProjectByIdQuery $query): ?Project
    {
        return $this->entityManager->find(Project::class, $query->id);
    }

}