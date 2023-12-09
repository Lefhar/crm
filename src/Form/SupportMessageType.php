<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupportMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'Sujet','required'=>true,'mapped' => false,'data_class'=>null
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'mapped' => false,
                'attr' => ['class' => 'form-control tinymce'],
                'required' => false  // Rendre le champ non obligatoire
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class, // Ajustez la classe ici
        ]);
    }
}