<?php
declare(strict_types=1);


namespace App\TimeTracking\Query;


class FindTimeLogQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 5,
        public ?string $project = null,
    )
    {
    }
}