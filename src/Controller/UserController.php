<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserController extends AbstractController
{

    /**
     * Display and process the profile edit form for the currently authenticated user.
     *
     * @param UserRepository $userRepository Repository used to retrieve the user by id.
     * @param EntityManagerInterface $entityManager Doctrine entity manager for persisting changes.
     * @param Request $request Current HTTP request.
     * @param int $id Identifier of the user to edit.
     * @param UserPasswordHasherInterface $passwordHasher Service for hashing user passwords.
     * @return Response Rendered edit page or redirect response depending on access and form state.
     */
    #[Route('/user/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request, int $id, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $userRepository->find($id);
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if ($user !== $this->getUser()) {
            return $this->redirectToRoute('app_recipe');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if($passwordHasher->isPasswordValid($user, $form->get('plainPassword')->getData())) {

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'User updated successfully!');

                return $this->redirectToRoute('app_recipe');
            }

            else {
                $this->addFlash('warning', 'Invalid password.');
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->redirectToRoute('app_user_edit', ['id' => $id]);
        }

        return $this->render('pages/user/edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }
}
