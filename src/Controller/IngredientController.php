<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use Doctrine\ORM\EntityManagerInterface;

final class IngredientController extends AbstractController
{
    /**
     * Display a paginated list of all ingredients
     *
     * @param IngredientRepository $ingredientsRepository Repository to fetch ingredients
     * @param PaginatorInterface $paginator Paginator service for pagination
     * @param Request $request HTTP request containing query parameters
     * @return Response Rendered template with paginated ingredients
     */
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(IngredientRepository $ingredientsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $ingredientsRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $pagination,
        ]);
    }

    /**
     * Create a new ingredient via form submission
     *
     * @param Request $request HTTP request containing form data
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @return Response Rendered form template or redirect on success
     */
    #[Route('/ingredient/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash('success', 'Ingredient added successfully!');

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? 422 : 200));
    }

    /**
     * Edit an existing ingredient via form submission
     *
     * @param IngredientRepository $ingredientRepository Repository to fetch the ingredient
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @param Request $request HTTP request containing form data
     * @param int $id ID of the ingredient to edit
     * @return Response Rendered form template or redirect on success
     */
    #[Route('/ingredient/{id}/edit', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $ingredient = $ingredientRepository->find($id);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Ingredient updated successfully!');

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete an ingredient from the database
     *
     * @param IngredientRepository $ingredientRepository Repository to fetch the ingredient
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @param int $id ID of the ingredient to delete
     * @return Response Redirect to the ingredient list with a flash message
     */
    #[Route('/ingredient/{id}/delete', name: 'app_ingredient_delete', methods: ['POST'])]
    public function delete(IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $ingredient = $ingredientRepository->find($id);

        if ($ingredient) {
            $entityManager->remove($ingredient);
            $entityManager->flush();

            $this->addFlash('success', 'Ingredient deleted successfully!');
        } else {
            $this->addFlash('error', 'Ingredient not found.');
        }

        return $this->redirectToRoute('app_ingredient');
    }

    /**
     * Display details of a single ingredient
     *
     * @param IngredientRepository $ingredientRepository Repository to fetch the ingredient
     * @param int $id ID of the ingredient to display
     * @return Response Rendered template with ingredient details
     */
    #[Route('/ingredient/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(IngredientRepository $ingredientRepository, int $id): Response
    {
        $ingredient = $ingredientRepository->find($id);

        if (!$ingredient) {
            throw $this->createNotFoundException('Ingredient not found.');
        }

        return $this->render('pages/ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

}
