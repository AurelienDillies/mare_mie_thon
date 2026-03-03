<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;
use App\Entity\Recipe;

class IngredientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName('Ingredient ' . $i);
            $ingredient->setPrice(mt_rand(1, 100)); // Random price between 1 and 100
            $ingredient->setCreatedAt(new \DateTimeImmutable());
            $this->addReference('ING' . $i, $ingredient); // Set a reference for the ingredient
            $manager->persist($ingredient);
        }

        for ($i = 1; $i <= 50; $i++) {
            $recipe = new Recipe();
            $recipe->setName('Recipe ' . $i);
            $recipe->setTime(mt_rand(10, 120)); // Random time between 10 and 120 minutes
            $recipe->setNbPeople(mt_rand(1, 10)); // Random number of people between 1 and 10
            $recipe->setDifficulty(mt_rand(1, 5)); // Random difficulty between 1 and 5
            $recipe->setDescription('This is the description for Recipe ' . $i);
            $recipe->setPrice(mt_rand(5, 50)); // Random price between 5 and 50
            $recipe->setIsFavorite(mt_rand(0, 1) === 1); // Randomly set as favorite or not
            $recipe->setCreatedAt(new \DateTimeImmutable());
            $recipe->setUpdatedAt(new \DateTimeImmutable());

            for ($j = 1; $j <= 5; $j++) {
                $ingredient = $this->getReference('ING' . mt_rand(1, 50), Ingredient::class); // Get a random ingredient reference
                $recipe->addIngredient($ingredient); // Add ingredient to the recipe
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
