<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("", name="user_")
 */
class UserController extends AbstractController
{
   private $em;

   public function __construct(EntityManagerInterface $em)
   {
      $this->em = $em;
   }

   /**
    * @Route("/register", name="register")
    */
   public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
   {
      $user = new User();
      $form = $this->createForm(UserType::class, $user);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
         $user->setPassword($encoded);

         $this->em->persist($user);
         $this->em->flush();

         $this->addFlash('success', 'Votre compte à bien été créé');

         return $this->redirectToRoute('main_home');
      }

      return $this->render('user/register.html.twig', array(
         'form' => $form->createView(),
      ));
   }

   /**
    * @Route("/login", name ="login")
    */
   public function login(AuthenticationUtils $authenticationUtils): Response
   {
      if ($this->getUser()) {
         return $this->redirectToRoute('main_home');
      }

      $error = $authenticationUtils->getLastAuthenticationError();
      if ($error) {
         $this->addFlash('danger', 'Erreur lors de la connexion');
      }

      return $this->render('user/login.html.twig', array(
         'lastUsername' => $authenticationUtils->getLastUsername(),
      ));
   }

   /**
    * @Route("/logout", name="logout")
    */
   public function logout()
   {
   }
}
