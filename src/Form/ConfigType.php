<?php

namespace App\Form;

use App\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['label'=>"Nom du site"])
            ->add('title',TextType::class,['label'=>"Titre avant slogan"])
            ->add('slogan',TextareaType::class,['label'=>'Zone Slogan','mapped'=>false,'attr'=>['class'=>'form-control tinymce'],'required'=>false])
            ->add('banner',FileType::class,['label'=>'Bannière','required'=>false,'attr'=>['accept'=>'image/*' , 'class'=>'form-control'],'data_class' => null,'mapped'=>false])
            ->add('about',TextareaType::class,['label'=>'Zone à propos','mapped'=>false,'attr'=>['class'=>'form-control tinymce'],'required'=>false])
            ->add('contact',TextareaType::class,['label'=>'Zone Contact','mapped'=>false,'attr'=>['class'=>'form-control tinymce'],'required'=>false])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
