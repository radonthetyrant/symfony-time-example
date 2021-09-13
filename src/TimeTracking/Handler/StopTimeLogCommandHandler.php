<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\Framework\User;
use App\TimeTracking\Command\StartTimeLogCommand;
use App\TimeTracking\Command\StopTimeLogCommand;
use App\TimeTracking\Exception\TimeLogSessionStartFailedException;
use App\TimeTracking\Project;
use App\TimeTracking\Query\GetProjectByIdQuery;
use App\TimeTracking\Query\GetUserProjectSessionTimeLogsQuery;
use App\TimeTracking\TimeLog;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Security\Core\Security;

class StopTimeLogCommandHandler implements MessageHandlerInterface
{

    public function __construct(
        private MessageBusInterface $queryBus,
        private EntityManagerInterface $entityManager,
        private Security $security,
    )
    {
    }

    public function __invoke(StopTimeLogCommand $command): void
    {
        /** @var User $user */
        if (null === ($user = $this->security->getUser())) {
            throw new AccessDeniedHttpException('Must be logged in to stop Time Log');
        }

        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch(new GetProjectByIdQuery($command->projectId))->last(HandledStamp::class);
        /** @var Project $project */
        if (null === ($project = $stamp->getResult())) {
            throw new NotFoundHttpException('Project not found.');
        }

        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch(new GetUserProjectSessionTimeLogsQuery($user->getId(), $project->getId(), false))->last(HandledStamp::class);
        /** @var Collection<TimeLog> $collection */
        $collection = $stamp->getResult();

        foreach ($collection as $timeLog) {
            $timeLog->setEndAt(new \DateTimeImmutable());
        }

        $this->entityManager->flush();
    }

}