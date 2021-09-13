<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Command\UpsertProjectCommand;
use App\TimeTracking\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpsertProjectCommandHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(UpsertProjectCommand $command): Project
    {
        $this->entityManager->persist($command->project);
        $this->entityManager->flush();

        return $command->project;
    }

}