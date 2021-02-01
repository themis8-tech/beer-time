<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("", name="user_")
 */
class UserController extends AbstractController
{
   private $em;
   private $repository;

   public function __construct(EntityManagerInterface $em)
   {
      $this->em = $em;
      $this->repository = $this->em->getRepository(User::class);
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

   /**
     * @Route("/reset/ask", name="reset_ask")
     */
    public function resetAsk(Request $request, MailerInterface $mailer): Response
    {
       //recuperation du mail entré dasn le formualire
        $email = $request->request->get('email');

        //retrouver le mail dasn la bdd
        $message = "";
        if( !empty( $email ) ){
            $user = $this->repository->findOneBy( array(
                'email' => $email
            ));

            //generation du token de reintialisation unique
            if( !empty( $user ) ){
                $token = bin2hex(random_bytes(24));
                $user->setToken( $token );
                //validité du reset de 2 heures
                $now = new DateTime();
                $now->add( new DateInterval('PT2H') );
                $user->setTokenExpiredAt( $now );

                //insertion du token dans la bdd sur l'user concerné
                $this->em->flush();

                //envoi du mail
                $mail = new Email();
                $mail->from('hello@rdelbaere.fr');
                $mail->to( $user->getEmail() );
                $mail->subject('Réinitialisation de mot de passe');

                //affichage de la vue dédié dans le corps du mail
                $view = $this->renderView( 'mail/reset-mail.html.twig', array(
                    'user' => $user,
                ));
                $mail->html($view);

                $mailer->send( $mail );
            }

            $message = "Si votre adresse email est présente dans notre base de données, vous allez recevoir un lien de réinitialisation valable 2 heures";
        }


        return $this->render('user/reset-ask.html.twig', array(
            'message' => $message
        ));
    }

    /**
     * @Route("/reset/confirm", name="reset_confirm")
     */

     //securité du mail de reintialisation
    public function resetConfirm(Request $request): Response
    {
       //verification du token de l'url si =celui bdd de l'user
        $token = $request->query->get('token');
        $user = $this->repository->findOneBy(array(
            'token' => $token,
        ));

        $now = new DateTime();
        if( empty($user) || $user->getTokenExpiredAt() < $now ){
            throw new NotFoundHttpException();
        }

        return new Response('Formulaire de changement de mot de passe');
    }
}
