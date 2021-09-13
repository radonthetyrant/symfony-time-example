<?php
declare(strict_types=1);


namespace App\TimeTracking\Controller;

use App\TimeTracking\Command\DeleteTimeLogCommand;
use App\TimeTracking\Command\UpsertTimeLogCommand;
use App\TimeTracking\Project;
use App\TimeTracking\Query\GetProjectByIdQuery;
use App\TimeTracking\TimeLog;
use App\TimeTracking\TimeLogType;
use App\TimeTracking\Query\FindTimeLogQuery;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/timelog")
 */
class TimeLogController extends AbstractController
{

    public function __construct(
        private MessageBusInterface $queryBus,
        private MessageBusInterface $commandBus,
    )
    {
    }

    /**
     * @Route("", name="timelog_list")
     */
    public function listTimeLogs(FindTimeLogQuery $query): Response
    {
        /** @var HandledStamp $result */
        $result = $this->queryBus->dispatch($query)->last(HandledStamp::class);
        /** @var Collection $collection */
        $collection = $result->getResult();

        return $this->render('time_log/list.html.twig', ['query' => $query, 'collection' => $collection]);
    }

    /**
     * @Route("/create", name="timelog_create", methods={"POST","GET"})
     */
    public function createTimeLog(Request $request): Response
    {
        $form = $this->createForm(TimeLogType::class, new TimeLog());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projectId = (int) $request->request->get('time_log')['project']['id'] ?? 1;

            /** @var HandledStamp $stamp */
            $stamp = $this->queryBus->dispatch(new GetProjectByIdQuery($projectId))->last(HandledStamp::class);
            /** @var ?Project $project */
            $project = $stamp->getResult();

            if (!$project instanceof Project) {
                throw new NotFoundHttpException('Project not found.');
            }

            /** @var TimeLog $timeLog */
            $timeLog = $form->getData();
            $timeLog->setProject($project);

            $this->commandBus->dispatch(new UpsertTimeLogCommand($timeLog));

            return $this->redirectToRoute('timelog_list');
        }

        return $this->render('time_log/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="timelog_edit")
     */
    public function editTimeLog(TimeLog $timeLog, Request $request): Response
    {
        $form = $this->createForm(TimeLogType::class, $timeLog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projectId = (int) $request->request->get('time_log')['project']['id'] ?? 1;

            /** @var HandledStamp $stamp */
            $stamp = $this->queryBus->dispatch(new GetProjectByIdQuery($projectId))->last(HandledStamp::class);
            /** @var ?Project $project */
            $project = $stamp->getResult();

            if (!$project instanceof Project) {
                throw new NotFoundHttpException('Project not found.');
            }

            /** @var TimeLog $timeLog */
            $timeLog = $form->getData();
            $timeLog->setProject($project);

            $this->commandBus->dispatch(new UpsertTimeLogCommand($timeLog));

            return $this->redirectToRoute('timelog_list');
        }

        return $this->render('time_log/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="timelog_delete", methods={"GET"})
     */
    public function deleteTimeLog(int $id): Response
    {
        $this->commandBus->dispatch(new DeleteTimeLogCommand($id));

        return $this->redirectToRoute('timelog_list');
    }

}