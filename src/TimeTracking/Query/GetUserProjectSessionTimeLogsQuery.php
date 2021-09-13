<?php
declare(strict_types=1);


namespace App\TimeTracking\Query;


class GetUserProjectSessionTimeLogsQuery
{
    public function __construct(
        public int  $userId,
        public int  $projectId,
        public bool $isRunningOnly,
    )
    {
    }
}