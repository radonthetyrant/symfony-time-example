<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Command\CreateProjectCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateProjectCommandHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(CreateProjectCommand $command)
    {
        $this->entityManager->persist($command->project);
        $this->entityManager->flush();
    }

}