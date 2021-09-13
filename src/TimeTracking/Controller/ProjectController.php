<?php
declare(strict_types=1);


namespace App\TimeTracking\Controller;

use App\TimeTracking\Query\FindProjectQuery;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{

    public function __construct(
        private MessageBusInterface $queryBus,
    )
    {
    }

    /**
     * @Route("", name="project_list")
     */
    public function listController(FindProjectQuery $query): Response
    {
        /** @var HandledStamp $result */
        $result = $this->queryBus->dispatch($query)->last(HandledStamp::class);
        /** @var Collection $collection */
        $collection = $result->getResult();

        return $this->render('project/list.html.twig', ['query' => $query, 'collection' => $collection]);
    }

}