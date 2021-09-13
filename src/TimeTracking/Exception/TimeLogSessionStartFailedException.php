<?php
declare(strict_types=1);


namespace App\TimeTracking\Exception;


use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TimeLogSessionStartFailedException extends \Exception
{

    public function __construct()
    {
        parent::__construct('Can\'t start a Time Log session if a previous one is still running', Response::HTTP_CONFLICT);
    }

}