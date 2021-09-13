<?php
declare(strict_types=1);


namespace App\TimeTracking\Command;


class DeleteTimeLogCommand
{
    public function __construct(
        public int $timeLogId
    )
    {
    }
}