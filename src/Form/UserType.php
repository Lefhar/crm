<?php

namespace App\Form;

use App\Entity\Config;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => false, // Si vous voulez permettre plusieurs choix
                'expanded' => false, // Si vous voulez afficher les choix sous forme de liste déroulante
                ])
            ->add('password',null,['required'=>false,'attr'=>['value'=>''],'mapped'=>false])

            ->add('isVerified')
            ->add('name')
            ->add('lastname')
            ->add('address')
            ->add('zipcode')
            ->add('city')
            ->add('config', EntityType::class, [
                'class' => Config::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ que vous souhaitez afficher
                'label' => 'Config',
                'placeholder' => 'Sélectionnez une configuration', // Optionnel : ajoutez une option vide
                'required' => true, // ou false selon vos besoins
            ]);
        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
