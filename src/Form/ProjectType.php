<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['label'=>'Nom du projet'])
            ->add('description', TextareaType::class, [
                'label' => 'Déscription',
                'mapped' => false,
                'attr' => ['class' => 'form-control tinymce'],
                'required' => false  // Rendre le champ non obligatoire
            ])
            ->add('startdate',DateTimeType::class,['label'=>'Début'])
            ->add('enddate',DateTimeType::class,['label'=>'Fin'])
            ->add('budget',MoneyType::class)
            ->add('user', EntityType::class, ['label'=>'Client',
                'class' => User::class,
'choice_label' => 'fullname',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
