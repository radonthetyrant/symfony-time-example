<?php
declare(strict_types=1);


namespace App\TimeTracking\Controller;

use App\TimeTracking\Query\FindProjectQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project", name="app_home")
 */
class ProjectController
{

    public function __construct()
    {
    }

    /**
     * @Route("")
     */
    public function listController(FindProjectQuery $query): Response
    {
        dump($query);

    }

}