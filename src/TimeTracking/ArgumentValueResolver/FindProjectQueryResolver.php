<?php
declare(strict_types=1);


namespace App\TimeTracking\ArgumentValueResolver;


use App\TimeTracking\Query\FindProjectQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FindProjectQueryResolver implements ArgumentValueResolverInterface
{

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield new FindProjectQuery(
            $request->query->getInt('page', 1),
            $request->query->getInt('perPage', 5),
        );
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === FindProjectQuery::class;
    }
}