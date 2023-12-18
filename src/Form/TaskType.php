<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class, [
                'label' => 'Déscription',
                'mapped' => false,
                'attr' => ['class' => 'form-control tinymce'],
                'required' => false  // Rendre le champ non obligatoire
            ])
            ->add('deadline',DateTimeType::class,['label'=>'Date limite'])
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Faible' => Task::LOW,
                    'Moyen' => Task::MODERATE,
                    'Haut' => Task::HIGHT,
                ],
                'label' => 'Priorité de la tâche',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Ouvert' => Task::STATUS_OPEN,
                    'En cours' => Task::STATUS_IN_PROGRESS,
                    'Fermé' => Task::STATUS_CLOSED,
                ],
                'label' => 'Statut de la tache',
            ])
            ->add('progress', ChoiceType::class, [
                'choices' => [
                    'Non commencé' => Task::PROGRESS_NOT_STARTED,
                    'En cours' => Task::PROGRESS_IN_PROGRESS,
                    'Terminé' => Task::PROGRESS_COMPLETED,
                ],
                'label' => 'Progression de la tâche',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
