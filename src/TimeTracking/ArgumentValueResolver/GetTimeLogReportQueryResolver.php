<?php
declare(strict_types=1);


namespace App\TimeTracking\ArgumentValueResolver;


use App\TimeTracking\Query\GetTimeLogReportQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class GetTimeLogReportQueryResolver implements ArgumentValueResolverInterface
{

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield new GetTimeLogReportQuery(
            $request->query->get('userId') ?? $request->request->get('userId'),
            $request->query->get('projectId') ?? $request->request->get('projectId'),
        );
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === GetTimeLogReportQuery::class;
    }
}