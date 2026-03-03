<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ingredient Name',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'placeholder' => 'Enter ingredient name',
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50,
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
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'EUR',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0.01,
                    'max' => 199.99,
                    'step' => 0.01,
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'The price cannot be null.'),
                    new Assert\Positive(message: 'The price must be a positive number.'),
                    new Assert\LessThan(
                        value: 200,
                        message: 'The price must be less than {{ compared_value }}.'
                    ),
                ],
            ])
            ->add(('submit'), SubmitType::class, [
                'label' => 'Add Ingredient',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
