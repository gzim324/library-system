<?php

namespace App\Form;

use App\Entity\Reader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("fullname", TextType::class)
            ->add("phone", TextType::class)
            ->add("email", TextType::class)
            ->add("city", TextType::class)
            ->add("street", TextType::class)
            ->add("zipCode", TextType::class)
            ->add("adress", TextType::class)
            ->add("submit", SubmitType::class, array('label' => 'New'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Reader::class,
        ]);
    }
}
