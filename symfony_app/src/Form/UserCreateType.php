<?php

namespace App\Form;

use App\DTO\UserDataDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'First Name',
                'attr' => ['placeholder' => 'Enter first name'],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Enter last name'],
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'placeholder' => 'Select gender',
                'label' => 'Gender',
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'string',
                'label' => 'Birthdate',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDataDTO::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
