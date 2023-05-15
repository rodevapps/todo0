<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Security;

use App\Entity\TodoList;
use App\Entity\TodoItem;
use App\Form\AddTodoItemFormType;
use Doctrine\Persistence\ManagerRegistry;

class TodoItemController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/todo-items', name: 'todoitem_home', methods: ["GET"])]
    public function index(): Response
    {   
        return $this->redirectToRoute('home');
    }

    #[Route('/todo-items/new', name: 'todoitem_new', methods: ["POST"])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $todoItem = new TodoItem();

        $entityManager = $doctrine->getManager();

        $id = $request->request->get('add_todo_item_form');

        if (is_numeric($id['todoListId'])) {
            $todoList = $doctrine->getRepository(TodoList::class)->find($id['todoListId']);

            if ($todoList) {
                $todoItem->setTodoListId($todoList);
            } else {
                $this->addFlash('error', 'List to add new item not found!');
                return $this->redirectToRoute('home');
            }
        }

        $form = $this->createForm(AddTodoItemFormType::class, $todoItem);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $todoItem = $form->getData();
            $todoItem->setDone(false);

            $entityManager->persist($todoItem);
            $entityManager->flush();

            $this->addFlash('success', 'New Todo Item created successfully!');

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('todoitem/new.html.twig', ['form' => $form]);
    }

    #[Route('/todo-items/update', name: 'todoitem_update', methods: ["POST"])]
    public function update(Request $request, ManagerRegistry $doctrine): Response
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $id = $request->request->get('id');
        $done = $request->request->get('done');

        $entityManager = $doctrine->getManager();
        $todoItem = $doctrine->getRepository(TodoItem::class)->find($id);

        if ($todoItem) {
            $todoItem->setDone((bool) $done);
            $entityManager->flush();

            $this->addFlash('success', 'Item updated successfully!');
        } else {
            $this->addFlash('error', 'Item not found to update!');
        }

        return $this->redirectToRoute('home');
    }
}
