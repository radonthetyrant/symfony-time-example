<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\Framework\User;
use App\TimeTracking\Command\UpsertProjectCommand;
use App\TimeTracking\Command\UpsertTimeLogCommand;
use App\TimeTracking\Project;
use App\TimeTracking\TimeLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

class UpsertTimeLogCommandHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    )
    {
    }

    public function __invoke(UpsertTimeLogCommand $command): TimeLog
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Session User required');
        }

        $command->timeLog->setUser($user);
        $this->entityManager->persist($command->timeLog);
        $this->entityManager->flush();

        return $command->timeLog;
    }

}