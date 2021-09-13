<?php
declare(strict_types=1);


namespace App\TimeTracking\Command;


class DeleteProjectCommand
{
    public function __construct(public int $projectId)
    {
    }
}