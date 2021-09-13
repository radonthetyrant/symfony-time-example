<?php
declare(strict_types=1);


namespace App\TimeTracking\Handler;


use App\TimeTracking\Query\GetTimeLogReportQuery;
use App\TimeTracking\TimeLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetTimeLogReportQueryHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(GetTimeLogReportQuery $query): array
    {
        $criteria = [];
        if ($query->userId !== null) {
            $criteria['user'] = $query->userId;
        }
        if ($query->projectId !== null) {
            $criteria['project'] = $query->projectId;
        }

        /** @var TimeLog[] $timeLogs */
        $timeLogs = $this->entityManager->getRepository(TimeLog::class)->findBy($criteria);

        $reportMonthly = [];
        $reportDaily = [];

        foreach ($timeLogs as $timeLog) {
            if ($timeLog->getEndAt() === null) {
                continue;
            }

            $dimUser = $timeLog->getUser()->getUsername();
            $dimProject = $timeLog->getProject()->getTitle();
            $dimYear = $timeLog->getStartAt()->format('Y');
            $dimMonth = $timeLog->getStartAt()->format('F');
            $dimDay = $timeLog->getStartAt()->format('D');
            $aggTimeMonthly = $reportMonthly[$dimUser][$dimProject][$dimYear][$dimMonth] ?? 0;
            $aggTimeDaily = $reportDaily[$dimUser][$dimProject][$dimYear][$dimMonth][$dimDay] ?? 0;

            $period = new \DatePeriod($timeLog->getStartAt(), new \DateInterval('PT1H'), $timeLog->getEndAt());
            $hours = iterator_count($period);

            $reportMonthly[$dimUser][$dimProject][$dimYear][$dimMonth] = ($aggTimeMonthly + $hours);
            $reportDaily[$dimUser][$dimProject][$dimYear][$dimMonth][$dimDay] = ($aggTimeDaily + $hours);
        }

        return [
            'monthly' => $reportMonthly,
            'daily' => $reportDaily,
        ];
    }

}