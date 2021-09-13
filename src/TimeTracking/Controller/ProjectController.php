<?php
declare(strict_types=1);


namespace App\TimeTracking\Controller;

use App\TimeTracking\Command\CreateProjectCommand;
use App\TimeTracking\Project;
use App\TimeTracking\ProjectType;
use App\TimeTracking\Query\FindProjectQuery;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
        private MessageBusInterface $commandBus,
    )
    {
    }

    /**
     * @Route("", name="project_list")
     */
    public function listProjects(FindProjectQuery $query): Response
    {
        /** @var HandledStamp $result */
        $result = $this->queryBus->dispatch($query)->last(HandledStamp::class);
        /** @var Collection $collection */
        $collection = $result->getResult();

        return $this->render('project/list.html.twig', ['query' => $query, 'collection' => $collection]);
    }

    /**
     * @Route("/create", name="project_create", methods={"POST","GET"})
     */
    public function createProject(Request $request): Response
    {
        $form = $this->createForm(ProjectType::class, new Project());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $this->commandBus->dispatch(new CreateProjectCommand($project));

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="project_edit")
     */
    public function editProject(Project $project, Request $request): Response
    {
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $this->commandBus->dispatch(new CreateProjectCommand($project));

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"GET"})
     */
    public function deleteProject(Project $project): Response
    {
        dump($project);

        $form = $this->createForm(ProjectType::class, $project);

        return $this->render('project/edit.html.twig', ['form' => $form->createView()]);
    }

}