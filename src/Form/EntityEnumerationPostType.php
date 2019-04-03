<?php

namespace App\Form;

use App\Entity\EntityPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityEnumerationPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('properties', ChoiceType::class,[
                'choices'=>$options['properties'],
                'multiple'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           "data_class"=>EntityPost::class,
            "csrf_protection"=>false
        ]);

        $resolver->setRequired('properties');
    }
}
