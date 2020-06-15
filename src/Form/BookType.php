<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category_id', ChoiceType::class,[
                'choices'=> [
                    'Children'=>Book::CHILDREN_CATEGORY,
                    'Fiction'=>Book::FICTION_CATEGORY
                ],
                'label' => 'category',
                'attr' => [
                    'class'=> 'form-control',
                ]
            ])
            ->add('name',TextType::class,[
                'attr' => [
                    'placeholder' => 'enter a name',
                    'class'=> 'form-control'
                ]
            ])
            ->add('author',TextType::class,[
                'attr' => [
                    'placeholder' => 'enter a author',
                    'class'=> 'form-control'
                ]
            ])
            ->add('description',TextareaType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter a description',
                    'class'=> 'form-control'
                ]
            ])
            ->add('image', FileType::class,[
                'label' => 'Please upload an image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5120k',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid jpg image',
                    ])
                ],
            ])
            ->add('unit_price',TextType::class,[
                'label' => 'Unit price',
                'attr' => [
                    'placeholder' => 'enter a price',
                    'class'=> 'form-control'
                ]
            ])
            ->add('save',SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-success mt-3'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
