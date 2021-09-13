<?php
declare(strict_types=1);


namespace App\TimeTracking\Query;


class GetTimeLogReportQuery
{
    public function __construct(
        public ?int $userId,
        public ?int $projectId,
    )
    {
    }
}