<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Recipe Name',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'placeholder' => 'Enter recipe name',
                    'class' => 'form-control',
                ],
            ])
            ->add('time', IntegerType::class, [
                'label' => 'Preparation Time (minutes)',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'placeholder' => 'Enter preparation time in minutes',
                    'class' => 'form-control',
                    'min' => 1,
                ],
            ])
            ->add('nb_people', IntegerType::class, [
                'required' => false,
                'label' => 'Number of People',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'placeholder' => 'Enter number of people',
                    'class' => 'form-control',
                    'min' => 1,
                ],
            ])
            ->add('difficulty', RangeType::class, [
                'required' => false,
                'label' => 'Difficulty',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5,
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'placeholder' => 'Enter recipe description',
                    'class' => 'form-control',
                ],
            ])
            ->add('price', MoneyType::class, [
                'required' => false,
                'label' => 'Price',
                'currency' => 'EUR',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0.01,
                    'step' => 0.01,
                ],
            ])
            ->add('is_favorite', CheckboxType::class, [
                'required' => false,
                'label' => 'Favorite',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'required' => false,
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add(('submit'), SubmitType::class, [
                'label' => 'Add Recipe',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
