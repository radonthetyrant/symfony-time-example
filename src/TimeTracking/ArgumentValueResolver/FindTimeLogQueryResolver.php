<?php
declare(strict_types=1);


namespace App\TimeTracking\ArgumentValueResolver;


use App\TimeTracking\Query\FindProjectQuery;
use App\TimeTracking\Query\FindTimeLogQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FindTimeLogQueryResolver implements ArgumentValueResolverInterface
{

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield new FindTimeLogQuery(
            $request->query->getInt('page', 1),
            $request->query->getInt('perPage', 5),
            $request->query->get('project'),
        );
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === FindTimeLogQuery::class;
    }
}