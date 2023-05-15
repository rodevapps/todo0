<?php

namespace App\Controller;

use App\Repository\TodoListRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'home')]
    public function index(TodoListRepository $todoListRepository): Response
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->security->getUser();

        $todoLists = $todoListRepository->findBy(array('user_id' => $user->getId()));

        return $this->render('home/index.html.twig', [
            'todoLists' => $todoLists,
        ]);
    }
}
