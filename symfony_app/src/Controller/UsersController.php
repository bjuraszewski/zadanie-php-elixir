<?php

namespace App\Controller;

use App\Contract\PhoenixApiInterface;
use App\DTO\UserDataDTO;
use App\DTO\UserFilterDTO;
use App\Form\UserCreateType;
use App\Form\UserFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsersController extends AbstractController
{
    public function __construct(
        private PhoenixApiInterface $phoenixApi
    ) {
    }

    #[Route('/', name: 'app_users')]
    public function index(Request $request): Response
    {
        $filterDTO = new UserFilterDTO();
        $filterForm = $this->createForm(UserFilterType::class, $filterDTO);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && !$filterForm->isValid()) {
            $this->addFlash('warning', 'Invalid search parameters. Showing all users.');
            $filterDTO = new UserFilterDTO();
        }

        $createForm = $this->createForm(UserCreateType::class);

        $users = $this->phoenixApi->getUsers($filterDTO);

        if (isset($users['error'])) {
            $this->addFlash('danger', 'Error getting users: ' . $users['error']);
        }

        return $this->render('users/index.html.twig', [
            'users' => $users,
            'filterForm' => $filterForm->createView(),
            'createForm' => $createForm->createView(),
        ]);
    }

    #[Route('/create', name: 'app_users_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $userData = new UserDataDTO();
        $form = $this->createForm(UserCreateType::class, $userData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->phoenixApi->createUser($userData);

            if (isset($result['error'])) {
                $this->addFlash('danger', 'Error creating user: ' . $result['error']);
            } else {
                $this->addFlash('success', 'User created successfully.');

                return $this->redirectToRoute('app_users', [
                    'first_name' => $userData->first_name,
                    'last_name' => $userData->last_name,
                    'gender' => $userData->gender,
                ]);
            }
        } else {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        return $this->redirectToRoute('app_users');
    }

    #[Route('/update/{id}', name: 'app_users_update', methods: ['POST'])]
    public function update(int $id, Request $request): Response
    {
        $userData = new UserDataDTO();
        $form = $this->createForm(UserCreateType::class, $userData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->phoenixApi->updateUser($id, $userData);

            if (isset($result['error'])) {
                $this->addFlash('danger', 'Error updating user: ' . $result['error']);
            } else {
                $this->addFlash('success', 'User updated successfully.');

                return $this->redirectToRoute('app_users', [
                    'first_name' => $userData->first_name,
                    'last_name' => $userData->last_name,
                    'gender' => $userData->gender,
                ]);
            }
        } else {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        return $this->redirectToRoute('app_users');
    }

    #[Route('/delete/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(int $id, Request $request): Response
    {
        $firstName = $request->request->get('first_name');
        $lastName = $request->request->get('last_name');
        $gender = $request->request->get('gender');

        if ($this->phoenixApi->deleteUser($id)) {
            $this->addFlash('success', 'User deleted successfully.');
        } else {
            $this->addFlash('danger', 'Error deleting user.');
        }

        return $this->redirectToRoute('app_users', [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender,
        ]);
    }
}
