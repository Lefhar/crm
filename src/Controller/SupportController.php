<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\SupportMessageType;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class SupportController extends AbstractController
{
    #[Route('/support', name: 'app_support')]
    public function supportPage(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager,UserRepository $userRepository)
    {
        $supportMessage = new Message();
        $form = $this->createForm(SupportMessageType::class, $supportMessage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Créez un nouveau ticket et associez-le au message de support

            $ticket = new Ticket();
            $ticket->setStatus(Ticket::STATUS_OPEN);
            $ticket->setSubject($form->get('subject')->getData());
            $ticket->setCreatedAt(new \DateTimeImmutable());
            $ticket->setUpdatedAt(new \DateTimeImmutable());
            $ticket->setUser($this->getUser());
            $supportMessage->setTicket($ticket);
            $supportMessage->setContent($form->get('content')->getData());
            $supportMessage->setCreatedAt(new \DateTimeImmutable());
            $supportMessage->setUserId($this->getUser());

            $entityManager->persist($ticket);
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

            $admin = $userRepository->findFirstAdmin();
            $emailAdmin = (new TemplatedEmail())
                ->from('contact@lefebvreharold.fr')
                ->to($admin->getEmail())
                ->subject($ticket->getSubject())
                ->context(['sujet'=>$ticket->getSubject(),'fullname'=>$ticket->getUser()->getFullName(),'message'=>$supportMessage->getContent(),'id'=>$ticket->getId(),'base'=>$baseurl])
                ->htmlTemplate('mailAdmin.html.twig');
            $mailer->send($emailAdmin);


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
        $ticketListe = $ticketRepository->findBy(['user' => $this->getUser()], ['id' => 'desc']);

        return $this->render('support/index.html.twig', [
            'ticket' => $ticketListe,
        ]);

    }

    #[Route('/support/ticket/{id}', name: 'app_support_ticket')]
    public function supportTicket(Ticket $ticket,MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager,TicketRepository $ticketRepository): RedirectResponse|Response
    {
        $ticketList = $ticketRepository->findBy(['user'=>$this->getUser()],['id'=>'desc']);
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
            return $this->redirectToRoute('app_support_ticket', ['id' => $ticket->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('support/show.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
            'ticketlist'=>$ticketList,
        ]);
    }
#[Route('/support/ticket/etat/{id}/{etat}', name: 'app_support_etat')]
public function supportEtat(Ticket $ticket,$etat,EntityManagerInterface $entityManager)
{
        $ticket->setStatus($etat);
        $entityManager->flush();
        if($etat == 3)
        {
            return $this->redirectToRoute('app_support_Liste');
        }else{
            return $this->redirectToRoute('app_support_ticket', ['id' => $ticket->getId()], Response::HTTP_SEE_OTHER);

        }


}

}
