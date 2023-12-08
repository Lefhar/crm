<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Ouvert' => Ticket::STATUS_OPEN,
                    'En cours' => Ticket::STATUS_IN_PROGRESS,
                    'Fermé' => Ticket::STATUS_CLOSED,
                ],
                'label' => 'Statut du ticket',
            ])
            ->add('created_at')
            ->add('updated_at')
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'fullName',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
