<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Command\DeleteTimeLogCommand;
use App\TimeTracking\TimeLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteTimeLogCommandHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(DeleteTimeLogCommand $command)
    {
        $this->entityManager->remove($this->entityManager->getReference(TimeLog::class, $command->timeLogId));
        $this->entityManager->flush();
    }

}