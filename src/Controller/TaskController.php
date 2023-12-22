<?php

namespace App\Controller;

use App\Entity\Subtasks;
use App\Entity\Task;
use App\Form\SubtaskFormType;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository, ProjectRepository $projectRepository,Request $request): Response
    {
        $project = $projectRepository->findOneBy(['user'=>$this->getUser()],['id'=>'asc']);
        $Subtasks = new Subtasks();
        $form = $this->createForm(SubtaskFormType::class, $Subtasks);
        $form->handleRequest($request);
        return $this->render('task/index.html.twig', [
            'subtaskForm'=>$Subtasks,
            'projects'=>$project,
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setDescription($form->get('description')->getData());
            $task->setDateCreated(new \DateTimeImmutable());
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET','POST'])]
    public function show( ProjectRepository $projectRepository,Task $task,Request $request,EntityManagerInterface $entityManager): Response
    {
        $project = $projectRepository->findBy(['user'=>$this->getUser()],['id'=>'asc']);
        dump($project);
        $Subtasks = new Subtasks();
        $form = $this->createForm(SubtaskFormType::class, $Subtasks);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Subtasks->setContent($form->get('content')->getData());
            $Subtasks->setTask($task);
            $Subtasks->setDate(new \DateTimeImmutable());
            $Subtasks->setUser($this->getUser());
            $entityManager->persist($Subtasks);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_show', ['id'=>$task->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('task/show.html.twig', [
            'subtaskForm'=>$form,
            'projects'=>$project,
            'tasks' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setDescription($form->get('description')->getData());
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
