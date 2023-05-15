<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\TodoList;
use App\Entity\TodoItem;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $passwordHasherFactory = new PasswordHasherFactory([
            User::class => ['algorithm' => 'auto'],
        
            PasswordAuthenticatedUserInterface::class => [
                'algorithm' => 'auto',
                'cost' => 15,
            ],
        ]);

        $userPasswordHasher = new UserPasswordHasher($passwordHasherFactory);

        for ($k = 0; $k < 4; $k++) {
            $user = new User();
            $user->setEmail("example{$k}@example.com");
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    'test123'
                )
            );

            $manager->persist($user);

            for ($i = 0; $i < 4; $i++) {
                $todoList = new TodoList();
                $todoList->setName("TodoList" . $i);
                $todoList->setUserId($user);

                $manager->persist($todoList);

                for ($j = 0; $j < 20; $j++) {
                    $todoItem = new TodoItem();
                    $todoItem->setName("TodoItem" . $j);
                    $todoItem->setDone(false);
                    $todoItem->setTodoListId($todoList);
        
                    $manager->persist($todoItem);
                }
            }
        }

        $manager->flush();
    }
}
