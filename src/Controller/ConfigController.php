<?php

namespace App\Controller;

use App\Entity\Config;
use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config')]
class ConfigController extends AbstractController
{
    #[Route('/', name: 'app_config_index', methods: ['GET'])]
    public function index(ConfigRepository $configRepository): Response
    {
        return $this->render('admin/config/index.html.twig', [
            'configs' => $configRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_config_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $config = new Config();
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form->get('banner')->getData() && $form->get('banner')->getData() != null)) {
                $fichier = $form->get('banner')->getData();
                $aMimeTypes = array("image/gif", "image/jpeg", "image/jpg", "image/png", "image/x-png", "image/tiff", "image/webp");
                if (in_array($fichier->getClientmimeType(), $aMimeTypes)) {
                    if ($fichier->move('assets/file/', $fichier->getClientOriginalName())) {
                        $config->setBanner($fichier->getClientOriginalName());

                    }
                }

            }
            $config->setSlogan($form->get('slogan')->getData());
            $config->setAbout($form->get('about')->getData());
            $config->setContact($form->get('contact')->getData());
            $entityManager->persist($config);
            $entityManager->flush();

            return $this->redirectToRoute('app_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/config/new.html.twig', [
            'config' => $config,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_config_show', methods: ['GET'])]
    public function show(Config $config): Response
    {
        return $this->render('admin/config/show.html.twig', [
            'config' => $config,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_config_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Config $config, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/config/edit.html.twig', [
            'config' => $config,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_config_delete', methods: ['POST'])]
    public function delete(Request $request, Config $config, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$config->getId(), $request->request->get('_token'))) {
            $entityManager->remove($config);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_config_index', [], Response::HTTP_SEE_OTHER);
    }
}
