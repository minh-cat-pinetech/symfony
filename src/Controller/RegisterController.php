<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createFormBuilder()
                ->add('username', null, [
                    'error_bubbling' => true,
                    'attr'  => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('password', RepeatedType::class, [
                    'type'              => PasswordType::class,
                    'required'          => true,
                    'error_bubbling'    => true,
                    'invalid_message'   => 'password do not match',
                    'first_options'     => ['label' => 'Password', 'attr' => ['class' => 'form-control']],
                    'second_options'    => ['label' => 'Confirm Password', 'attr' => ['class' => 'form-control']],
                ])
                ->add('register', SubmitType::class, [
                    'attr'  => [
                        'class' => 'btn btn-success'
                    ]
                ])
                ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword($passwordHasher->hashPassword($user, $data['password'] ?? ''));

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_login'));
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
