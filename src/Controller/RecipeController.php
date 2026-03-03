<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;

final class RecipeController extends AbstractController
{
    /**
     * Display a paginated list of all recipes
     *
     * @param RecipeRepository $recipeRepository Repository to fetch recipes
     * @param PaginatorInterface $paginator Paginator service for pagination
     * @param Request $request HTTP request containing query parameters
     * @return Response Rendered template with paginated recipes
     */
    #[Route('/recipe', name: 'app_recipe', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $recipeRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $pagination,
        ]);
    }

    /**
     * Create a new recipe via form submission
     *
     * @param Request $request HTTP request containing form data
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @return Response Rendered form template or redirect on success
     */
    #[Route('/recipe/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Recipe added successfully!');

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? 422 : 200));
    }

    /**
     * Edit an existing recipe via form submission
     *
     * @param RecipeRepository $recipeRepository Repository to fetch the recipe
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @param Request $request HTTP request containing form data
     * @param int $id ID of the recipe to edit
     * @return Response Rendered form template or redirect on success
     */
    #[Route('/recipe/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(RecipeRepository $recipeRepository, EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $recipe = $recipeRepository->find($id);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUpdatedAt(); // Update the updated_at field
            $entityManager->flush();

            $this->addFlash('success', 'Recipe updated successfully!');
            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a recipe from the database
     *
     * @param RecipeRepository $recipeRepository Repository to fetch the recipe
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @param int $id ID of the recipe to delete
     * @return Response Redirect to the recipe list with a flash message
     */
    #[Route('/recipe/{id}/delete', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(RecipeRepository $recipeRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        if ($recipe) {
            $entityManager->remove($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Recipe deleted successfully!');
        } else {
            $this->addFlash('error', 'Recipe not found.');
        }

        return $this->redirectToRoute('app_recipe');
    }

    /**
     * Display details of a single recipe
     *
     * @param RecipeRepository $recipeRepository Repository to fetch the recipe
     * @param int $id ID of the recipe to display
     * @return Response Rendered template with recipe details
     */
    #[Route('/recipe/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found.');
        }

        return $this->render('pages/recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

}
