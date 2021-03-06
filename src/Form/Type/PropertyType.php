<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 16.08.2018
 * Time: 10:39.
 */

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\District;
use App\Entity\Feature;
use App\Entity\Metro;
use App\Entity\Neighborhood;
use App\Entity\Property;
use App\Entity\User;
use App\Form\EventSubscriber\AddAgentFieldSubscriber;
use App\Form\EventSubscriber\AddDistrictFieldSubscriber;
use App\Form\EventSubscriber\AddMetroFieldSubscriber;
use App\Form\EventSubscriber\AddNeighborhoodFieldSubscriber;
use App\Form\EventSubscriber\UpdateDistrictFieldSubscriber;
use App\Form\EventSubscriber\UpdateMetroFieldSubscriber;
use App\Form\EventSubscriber\UpdateNeighborhoodFieldSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

final class PropertyType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_city',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.city',
            ])
            ->add('district', EntityType::class, [
                'class' => District::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_district',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.district',
                'required' => false,
                'choices' => [],
            ])
            ->add('neighborhood', EntityType::class, [
                'class' => Neighborhood::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_neighborhood',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.neighborhood',
                'required' => false,
                'choices' => [],
            ])
            ->add('metro_station', EntityType::class, [
                'class' => Metro::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_metro_station',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.metro_station_name',
                'required' => false,
                'choices' => [],
            ])
            ->add('dealType', EntityType::class, [
                'class' => DealType::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.deal_type',
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
            ->add('max_guests', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.max_guests',
            ])
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.title',
            ])
            ->add('meta_title', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.meta_title',
            ])
            ->add('meta_description', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.meta_description',
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
                    'attr' => [
                        'class' => 'custom-control-input',
                    ],
                    'label' => 'label.available_now',
                    'label_attr' => [
                        'class' => 'custom-control-label',
                    ],
                ]
            )
            ->add('features', EntityType::class, [
                'class' => Feature::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'multiple' => true,
                'required' => false,
                'label' => 'label.features',
                //'expanded' => true
            ])
            ->add('priority_number', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.priority_number',
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'full_name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.agent',
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control summer-note',
                    'rows' => '7',
                ],
                'label' => 'label.content',
            ]);

        $builder->addEventSubscriber(new AddAgentFieldSubscriber($this->security));

        $builder->addEventSubscriber(new AddNeighborhoodFieldSubscriber());
        $builder->get('city')->addEventSubscriber(new UpdateNeighborhoodFieldSubscriber());

        $builder->addEventSubscriber(new AddDistrictFieldSubscriber());
        $builder->get('city')->addEventSubscriber(new UpdateDistrictFieldSubscriber());

        $builder->addEventSubscriber(new AddMetroFieldSubscriber());
        $builder->get('city')->addEventSubscriber(new UpdateMetroFieldSubscriber());
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
