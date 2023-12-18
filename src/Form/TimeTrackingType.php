<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TimeTracking;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeTrackingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('starttime',DateTimeType::class,['label'=>'Début'])
            ->add('endtime',DateTimeType::class,['label'=>'Fin'])
            ->add('hours',NumberType::class,['label'=>'Nombre d\'heure'])
            ->add('task', EntityType::class, ['label'=>'Tâche',
                'class' => Task::class,
'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimeTracking::class,
        ]);
    }
}
