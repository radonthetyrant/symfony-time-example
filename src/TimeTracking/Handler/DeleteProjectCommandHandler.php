<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Command\DeleteProjectCommand;
use App\TimeTracking\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteProjectCommandHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(DeleteProjectCommand $command)
    {
        $this->entityManager->remove($this->entityManager->getReference(Project::class, $command->projectId));
        $this->entityManager->flush();
    }

}