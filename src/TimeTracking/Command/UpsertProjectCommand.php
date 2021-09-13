<?php
declare(strict_types=1);


namespace App\TimeTracking\Command;


use App\TimeTracking\Project;

class UpsertProjectCommand
{
    public function __construct(
        public Project $project
    )
    {
    }
}