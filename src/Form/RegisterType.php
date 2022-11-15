<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
        ;

        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
            $user = $event->getData();
            $factory = new PasswordHasherFactory([
                User::class => ['algorithm' => 'auto'],
            ]);
            $passwordHasher = new UserPasswordHasher($factory);
            $passwordEncode = $passwordHasher->hashPassword($user, $user->getPassword() ?? '');
            $user->setPassword($passwordEncode);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
