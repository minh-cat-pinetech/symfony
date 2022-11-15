<?php

namespace App\Controller;

use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
{
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(RegisterType::class);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('auth.login'));
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
