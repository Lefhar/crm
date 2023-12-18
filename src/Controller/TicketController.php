<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\SupportMessageType;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET', 'POST'])]
    public function show(Ticket $ticket,Request $request, EntityManagerInterface $entityManager,TicketRepository $ticketRepository,MailerInterface $mailer): Response
    {
        $ticketList = $ticketRepository->findAll();
        $supportMessage = new Message();
        $form = $this->createForm(SupportMessageType::class, $supportMessage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setUpdatedAt(new \DateTimeImmutable());
            $supportMessage->setCreatedAt(new \DateTimeImmutable());
            $supportMessage->setTicket($ticket);
            $supportMessage->setUserId($this->getUser());
            $supportMessage->setContent($form->get('content')->getData());
            $entityManager->persist($supportMessage);
            $entityManager->flush();
            $baseurl = $request->getSchemeAndHttpHost();
            $email = (new TemplatedEmail())
                ->from('contact@lefebvreharold.fr')
                ->to($ticket->getUser()->getEmail())
                ->subject($ticket->getSubject())
                ->context(['sujet'=>$ticket->getSubject(),'fullname'=>$ticket->getUser()->getFullName(),'message'=>$supportMessage->getContent(),'id'=>$ticket->getId(),'base'=>$baseurl])
                ->htmlTemplate('mail.html.twig');
            $mailer->send($email);
            $this->addFlash('success', 'Message ajouté avec succès !');
            return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
            'form'=>$form,
            'ticketlist'=>$ticketList
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }
    public function CountTicket(TicketRepository $ticketRepository)
    {
        $count = $ticketRepository->countTicketsByStatus();
        return $this->render('countticket.html.twig',['count'=>$count]);
    }

    #[Route('/ticket/etat/{id}/{etat}', name: 'app_ticket_etat')]
    public function ticketEtat(Ticket $ticket,$etat,EntityManagerInterface $entityManager)
    {
        $ticket->setStatus($etat);
        $entityManager->flush();
        if($etat == 3)
        {
            return $this->redirectToRoute('app_ticket_index');
        }else{
            return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()], Response::HTTP_SEE_OTHER);

        }


    }
}
