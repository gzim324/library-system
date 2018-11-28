<?php

namespace App\Form;

use App\Entity\Book;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('category', EntityType::class, array(
                'class' => 'App\Entity\Category',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.deleted = :status')
                        ->setParameter('status', false);
                },
                'choice_label' => function ($type) {
                    return $type->getName();
                },
                'required'   => false,
            ))
            ->add('isbn', TextType::class)
            ->add('file', FileType::class)
            ->add('description', TextareaType::class, array('attr' => array('rows' => '8')))
            ->add('save', SubmitType::class, array('label' => 'ADD'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Book::class,
        ]);
    }
}
