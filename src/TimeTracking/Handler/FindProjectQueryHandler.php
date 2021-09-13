<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Project;
use App\TimeTracking\Query\FindProjectQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindProjectQueryHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(FindProjectQuery $query): Collection
    {
        $result = $this->entityManager->getRepository(Project::class)->findBy(
            [],
            limit: $query->perPage,
            offset: max(0, $query->page - 1) * $query->perPage,
        );

        return new ArrayCollection($result);
    }

}