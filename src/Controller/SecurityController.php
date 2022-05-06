<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/security", name="app_security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/register/{user_type}", name="security_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, $user_type): Response
    {
        $user = new User();

        if ($user_type === 'admin' AND $this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $user->setRoles(['ROLE_ADMIN']);
        }

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid())
        {
            $password_hash = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password_hash);

            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute("security_login");
        }

        return $this->render('security/index.html.twig', [
            'controller_name' => "Inscription",
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(): Response
    {
        return $this->render('security/login.html.twig', [

        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }

}