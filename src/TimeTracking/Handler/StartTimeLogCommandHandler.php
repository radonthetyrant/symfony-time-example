<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\Framework\User;
use App\TimeTracking\Command\StartTimeLogCommand;
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

class StartTimeLogCommandHandler implements MessageHandlerInterface
{

    public function __construct(
        private MessageBusInterface $queryBus,
        private EntityManagerInterface $entityManager,
        private Security $security,
    )
    {
    }

    /**
     * @throws TimeLogSessionStartFailedException
     */
    public function __invoke(StartTimeLogCommand $command): TimeLog
    {
        /** @var User $user */
        if (null === ($user = $this->security->getUser())) {
            throw new AccessDeniedHttpException('Must be logged in to start Time Log');
        }

        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch(new GetProjectByIdQuery($command->projectId))->last(HandledStamp::class);
        /** @var Project $project */
        if (null === ($project = $stamp->getResult())) {
            throw new NotFoundHttpException('Project not found.');
        }

        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch(new GetUserProjectSessionTimeLogsQuery($user->getId(), $project->getId(), true))->last(HandledStamp::class);
        /** @var Collection $collection */
        $collection = $stamp->getResult();

        if ($collection->count() > 0) {
            throw new TimeLogSessionStartFailedException();
        }

        $newTimeLog = (new TimeLog())
            ->setUser($user)
            ->setProject($project)
            ->setStartAt(new \DateTimeImmutable());

        $this->entityManager->persist($newTimeLog);
        $this->entityManager->flush();

        return $newTimeLog;
    }

}