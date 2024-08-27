<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

      /**
     * @Route("/", name="task_list")
     */
    #[Route('/', name: 'task_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $tasks = $em->getRepository(Task::class)->findAll();

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/create", name="task_create")
     */
    #[Route('/task/create', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Créer une tache',
        ]);
    }

    /**
     * @Route("/task/{id}", name="task_edit")
     */
    #[Route('/task/{id}', name: 'task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier la tâche',
        ]);
    }

    /**
     * @Route("/task/delete/{id}", name="task_delete")
     */
    #[Route('/task/{id}', name: 'task_delete', methods: ['DELETE'])]public function delete(Task $task, EntityManagerInterface $em): Response
    {
        $em->remove($task);
        $em->flush();
    
        return $this->redirectToRoute('task_list');
    }
    
}
