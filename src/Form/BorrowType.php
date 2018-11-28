<?php

namespace App\Form;

use App\Entity\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reader', EntityType::class, array(
                'placeholder' => 'Choose reader',
                'class' => 'App\Entity\Reader',
                'choice_label' => function ($type) {
                    return $type->getFullname();
                },
                'required'   => false,
            ))
            ->add('deadline', DateTimeType::class, array('label' => 'Deadline', "data" => new \DateTime("+30 day")))
            ->add('save', SubmitType::class, array('label' => 'Borrow'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Unit::class,
        ]);
    }
}
