<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\SupportMessageType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SupportController extends AbstractController
{
    #[Route('/support', name: 'app_support')]
    public function supportPage(Request $request, EntityManagerInterface $entityManager)
    {
        $supportMessage = new Message();
        $form = $this->createForm(SupportMessageType::class, $supportMessage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Créez un nouveau ticket et associez-le au message de support
            dump($form);
            $ticket = new Ticket();
            $ticket->setStatus(Ticket::STATUS_OPEN);
            $ticket->setSubject($form->get('subject')->getData());
            $ticket->setCreatedAt(new \DateTimeImmutable());
            $ticket->setUpdatedAt(new \DateTimeImmutable());
            $ticket->setUser($this->getUser());
            $supportMessage->setTicketId($ticket);
            $supportMessage->setContent($form->get('content')->getData());
            $supportMessage->setCreatedAt(new \DateTimeImmutable());
            $supportMessage->setUserId($this->getUser());

            $entityManager->persist($ticket);
            $entityManager->persist($supportMessage);
            $entityManager->flush();

            // Ajoutez ici la gestion de la redirection ou un message de confirmation
            $this->addFlash('success', 'Ticket créé avec succès !');

            return $this->redirectToRoute('app_support_Liste');
        }

        return $this->render('support/support_page.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/support/ticket/list', name: 'app_support_Liste')]
    public function supportList(TicketRepository $ticketRepository)
    {
        $ticketListe =  $ticketRepository->findBy(['user'=>$this->getUser()],['id'=>'desc']);

        return $this->render('support/index.html.twig', [
            'ticket' => $ticketListe,
        ]);

    }
    #[Route('/support/ticket/{id}', name: 'app_support_ticket')]
    public function supportTicket(Ticket $ticket)
    {

    }
}
