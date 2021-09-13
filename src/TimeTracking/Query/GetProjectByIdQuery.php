<?php
declare(strict_types=1);


namespace App\TimeTracking\Query;


class GetProjectByIdQuery
{
    public function __construct(public int $id)
    {
    }
}