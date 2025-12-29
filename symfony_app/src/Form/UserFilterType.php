<?php

namespace App\Form;

use App\DTO\UserFilterDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'required' => false,
                'label' => 'First Name',
            ])
            ->add('last_name', TextType::class, [
                'required' => false,
                'label' => 'Last Name',
            ])
            ->add('gender', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Any' => '',
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'label' => 'Gender',
            ])
            ->add('born_after', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'input' => 'string',
                'label' => 'Born After',
            ])
            ->add('born_before', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'input' => 'string',
                'label' => 'Born Before',
            ])
            ->add('sort_by', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'ID' => 'id',
                    'First Name' => 'first_name',
                    'Last Name' => 'last_name',
                    'Gender' => 'gender',
                    'Birthdate' => 'birthdate',
                ],
                'label' => 'Sort By',
            ])
            ->add('sort_order', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Ascending' => 'asc',
                    'Descending' => 'desc',
                ],
                'label' => 'Sort Order',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserFilterDTO::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
