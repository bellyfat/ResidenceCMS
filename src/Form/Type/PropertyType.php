<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 16.08.2018
 * Time: 10:39.
 */

namespace App\Form\Type;

use App\Entity\Area;
use App\Entity\Category;
use App\Entity\Locality;
use App\Entity\Operation;
use App\Entity\Property;
use App\Form\EventSubscriber\AddAreaFieldSubscriber;
use App\Form\EventSubscriber\UpdateAreaFieldSubscriber;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PropertyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('locality', EntityType::class, [
                'class' => Locality::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_city',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.locality',
            ])
            ->add('area', EntityType::class, [
                'class' => Area::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_area',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.area',
                'choices' => [],
            ])
            ->add('operation', EntityType::class, [
                'class' => Operation::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.operation',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.category',
            ])
            ->add('bathrooms_number', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.bathrooms_number',
            ])
            ->add('bedrooms_number', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.bedrooms_number',
            ])
            ->add('max_occupancy', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.max_occupancy',
            ])
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.title',
            ])
            ->add('description', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.description',
            ])
            ->add('address', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.address',
            ])
            ->add('latitude', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.latitude',
            ])
            ->add('longitude', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.longitude',
            ])
            ->add('show_map', null, [
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label' => 'label.show_map',
                'label_attr' => [
                    'class' => 'custom-control-label',
                ],
            ])
            ->add('price', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.price',
            ])
            ->add('price_type', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.price_type',
            ])
            ->add('available_now', null, [
                    'data' => true,
                    'attr' => [
                        'class' => 'custom-control-input',
                    ],
                    'label' => 'label.available_now',
                    'label_attr' => [
                        'class' => 'custom-control-label',
                    ],
                ]
            )
            ->add('content', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#ffffff',
                ],
                'label' => 'label.content',
            ]);

        $builder->addEventSubscriber(new AddAreaFieldSubscriber());
        $builder->get('locality')->addEventSubscriber(new UpdateAreaFieldSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}