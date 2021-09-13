<?php
declare(strict_types=1);


namespace App\TimeTracking\Controller;

use App\TimeTracking\Command\DeleteTimeLogCommand;
use App\TimeTracking\Command\StartTimeLogCommand;
use App\TimeTracking\Command\StopTimeLogCommand;
use App\TimeTracking\Command\UpsertTimeLogCommand;
use App\TimeTracking\Exception\TimeLogSessionStartFailedException;
use App\TimeTracking\Project;
use App\TimeTracking\Query\FindProjectQuery;
use App\TimeTracking\Query\GetProjectByIdQuery;
use App\TimeTracking\Query\GetTimeLogReportQuery;
use App\TimeTracking\Report\ReportAsCsvGenerator;
use App\TimeTracking\TimeLog;
use App\TimeTracking\TimeLogType;
use App\TimeTracking\Query\FindTimeLogQuery;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{

    public function __construct(
        private MessageBusInterface $queryBus,
        private ReportAsCsvGenerator $csvGenerator,
    )
    {
    }

    /**
     * @Route("/generate", name="generate_report")
     */
    public function generateReport(Request $request, GetTimeLogReportQuery $query): Response
    {
        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch($query)->last(HandledStamp::class);
        /** @var array $reports */
        $reports = $stamp->getResult();

        return $this->render('report.html.twig', ['reports' => $reports]);
    }

    /**
     * @Route("/generate/file.csv", name="generate_csv_report")
     */
    public function generateCsvReport(): Response
    {
        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch(new FindTimeLogQuery(null, null))->last(HandledStamp::class);
        /** @var Collection<TimeLog> $collection */
        $collection = $stamp->getResult();

        return $this->csvGenerator->asStreamedResponse($collection);
    }

}