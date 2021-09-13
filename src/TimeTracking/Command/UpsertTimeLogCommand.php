<?php
declare(strict_types=1);


namespace App\TimeTracking\Command;


use App\TimeTracking\TimeLog;

class UpsertTimeLogCommand
{
    public function __construct(
        public TimeLog $timeLog
    )
    {
    }
}