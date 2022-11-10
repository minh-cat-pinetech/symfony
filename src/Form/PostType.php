<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'error_bubbling'    => true,
                'required'          => true,
                'label'             => 'Title',
                'label_attr'        => ['class' => 'form-label'],
                'attr'              => [
                    'class' => 'form-control'
                ]
            ])
            ->add('excerpt', null, [
                'error_bubbling'    => true,
                'required'          => true,
                'label'             => 'Excerpt',
                'label_attr'        => ['class' => 'form-label'],
                'attr'              => [
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'error_bubbling'    => true,
                'required'          => true,
                'label'             => 'Content',
                'label_attr'        => ['class' => 'form-label'],
                'attr'              => [
                    'class' => 'form-control',
                    'rows'  => '10',
                    'cols'  => '30'
                ]
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/jpg',
                            'application/x-jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG',
                    ])
                ],
                'attr' => [
                    'id' => 'upload_image',
                    'style' => 'display: none;'
                ],
                'label' => 'Upload Image',
                'label_attr' => [
                    'class' => 'btn btn-primary',
                    'for'   => 'upload_image'
                ]
            ])
            ->add('seo_keyword', null, [
                'error_bubbling'    => true,
                'required'          => true,
                'label'             => 'SEO Keyword',
                'label_attr'        => ['class' => 'form-label'],
                'attr'              => [
                    'class' => 'form-control'
                ]
            ])
            ->add('tags', null, [
                'error_bubbling'    => true,
                'required'          => true,
                'label'             => 'Tags',
                'label_attr'        => ['class' => 'form-label'],
                'attr'              => [
                    'class' => 'form-control'
                ]
            ])
            ->add('categories', null, [
                'error_bubbling'    => true,
                'required'          => true,
                'label'             => 'Categories',
                'label_attr'        => ['class' => 'form-label'],
                'attr'              => [
                    'class' => 'form-control'
                ]
            ])
            ->add('state', ChoiceType::class, [
                'expanded'          => true,
                'choices'           => [
                    'show'          => '0',
                    'hide'          => '1'
                ],
                'attr'              => [
                    'class'         => 'radio'
                ],
                'empty_data'        => '0',
                'label_attr'        => ['class' => 'state-label']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
