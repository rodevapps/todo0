<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Security;

use App\Entity\TodoList;
use App\Form\AddTodoListFormType;
use Doctrine\Persistence\ManagerRegistry;

class TodoListController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/todo-lists', name: 'todolist_home', methods: ["GET"])]
    public function index(): Response
    {   
        return $this->redirectToRoute('home');
    }

    #[Route('/todo-lists/new', name: 'todolist_new', methods: ["GET", "POST"])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(AddTodoListFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoList = new TodoList();
            $todoList = $form->getData();

            $user = $this->security->getUser();

            $todoList->setUserId($user);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($todoList);
            $entityManager->flush();

            $this->addFlash('success', 'New Todo List created successfully!');

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('todolist/new.html.twig', ['form' => $form]);
    }
}
