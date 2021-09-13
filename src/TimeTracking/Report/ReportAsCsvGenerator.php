<?php
declare(strict_types=1);


namespace App\TimeTracking\Report;


use App\TimeTracking\TimeLog;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportAsCsvGenerator
{

    /**
     * @param Collection<TimeLog> $collection
     * @return StreamedResponse
     */
    public function asStreamedResponse(Collection $collection): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($collection) {
            print implode(',', ['id', 'username', 'project', 'startAt', 'endAt', 'hours']) . PHP_EOL;
            foreach ($collection as $timeLog) {

                if ($timeLog->getEndAt() instanceof \DateTimeImmutable) {
                    $period = new \DatePeriod($timeLog->getStartAt(), new \DateInterval('PT1H'), $timeLog->getEndAt());
                    $hours = iterator_count($period);
                } else {
                    $hours = null;
                }

                print implode(',',
                        [
                            $timeLog->getId(),
                            $timeLog->getUser()->getUsername(),
                            $timeLog->getProject()->getTitle(),
                            $timeLog->getStartAt()->format(DATE_RFC3339),
                            $timeLog->getEndAt() instanceof \DateTimeImmutable ? $timeLog->getEndAt()->format(DATE_RFC3339) : null,
                            $hours,
                        ]
                    ) . PHP_EOL;
            }
        });
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

}