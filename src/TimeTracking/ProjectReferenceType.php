<?php

namespace App\TimeTracking;

use App\TimeTracking\Query\FindProjectQuery;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectReferenceType extends AbstractType
{
    public function __construct(
        private MessageBusInterface $queryBus
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch(new FindProjectQuery(1, 500))->last(HandledStamp::class);
        /** @var Collection<Project> $projects */
        $projects = $stamp->getResult();

        $choices = [];
        foreach ($projects as $project) {
            $choices[$project->getTitle()] = $project->getId();
        }

        $builder->add('id', ChoiceType::class, [
            'choices'  => $choices,
            'label' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
