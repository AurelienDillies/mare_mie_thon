<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 180,
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'The email cannot be blank.'),
                    new Assert\Email(message: 'The email "{{ value }}" is not a valid email.'),
                    new Assert\Length(
                        min: 2,
                        max: 180,
                        minMessage: 'The email must be at least {{ limit }} characters long.',
                        maxMessage: 'The email cannot be longer than {{ limit }} characters.'
                    ),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => 6,
                        'maxlength' => 4096,
                    ],
                    'label' => 'Password',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => 6,
                        'maxlength' => 4096,
                    ],
                    'label' => 'Repeat Password',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'label' => 'Name',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'The name cannot be blank.'),
                    new Assert\Length(
                        min: 2,
                        max: 50,
                        minMessage: 'The name must be at least {{ limit }} characters long.',
                        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
                    ),
                ],
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'label' => 'Username',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\Length(
                        min: 2,
                        max: 50,
                        minMessage: 'The username must be at least {{ limit }} characters long.',
                        maxMessage: 'The username cannot be longer than {{ limit }} characters.'
                    ),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
