<?php

namespace App\Controller;

use App\Entity\TimeTracking;
use App\Form\TimeTrackingType;
use App\Repository\TimeTrackingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/time/tracking')]
class TimeTrackingController extends AbstractController
{
    #[Route('/', name: 'app_time_tracking_index', methods: ['GET'])]
    public function index(TimeTrackingRepository $timeTrackingRepository): Response
    {
        return $this->render('time_tracking/index.html.twig', [
            'time_trackings' => $timeTrackingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_time_tracking_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $timeTracking = new TimeTracking();
        $form = $this->createForm(TimeTrackingType::class, $timeTracking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeTracking);
            $entityManager->flush();

            return $this->redirectToRoute('app_time_tracking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_tracking/new.html.twig', [
            'time_tracking' => $timeTracking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_tracking_show', methods: ['GET'])]
    public function show(TimeTracking $timeTracking): Response
    {
        return $this->render('time_tracking/show.html.twig', [
            'time_tracking' => $timeTracking,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_time_tracking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TimeTracking $timeTracking, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimeTrackingType::class, $timeTracking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_time_tracking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_tracking/edit.html.twig', [
            'time_tracking' => $timeTracking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_tracking_delete', methods: ['POST'])]
    public function delete(Request $request, TimeTracking $timeTracking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeTracking->getId(), $request->request->get('_token'))) {
            $entityManager->remove($timeTracking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_time_tracking_index', [], Response::HTTP_SEE_OTHER);
    }
}
