<?php

namespace App\Controller;


use Generator;
use App\Entity\User;
use App\Entity\Event;
use App\Form\EventType;
use App\Entity\Participation;
use App\Service\EventService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    private $eventService;
    private $em;


    public function __construct(EntityManagerInterface $em, EventService $eventService)
    {
        $this->eventService = $eventService;
        $this->em = $em;
    }

    /**
     * @Route("", name="list")
     */
    //service request ne peut pas etre injecté dans construct :il faut requeststack
    //on peut injecter dans une methode autre

    public function list(Request $request): Response
    {
        $query = $request->query->get('q');
        $sort = $request->query->get('sort');

        $events = $this->eventService->buildResult($query, $sort);
        return $this->render('event/list.html.twig', array(
            'events' => $events,
            'query' => $query,
        ));
    }


    /**
     * @Route("/{id}", name="display" ,requirements={"id"="\d+"})
     *
     */
    public function display(ParticipationRepository $participationRepository, $id): Response
    {
        $event = $this->eventService->getOne($id);

        if (empty($event)) {
            throw new NotFoundHttpException("l'evenement n'existe pas");
        }
        if( $this->getUser() ){
            $hasParticipation = $participationRepository->count(array(
                'event' => $event,
                'user' => $this->getUser(),
            ));
        }else{
            $hasParticipation = 0;
        }
        
        return $this->render('event/display.html.twig', array(
            'event' => $event,
            // relié a event service
            'participantCounter' => $this->eventService->countParticipant($event),
            'hasParticipation' => $hasParticipation,
        ));
    }

    /**
     * @Route("/create", name="create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, UserRepository $userRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        //traitement du formulaire
        //recuperation des donnees :hydratation
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setOwner($this->getUser());

            //ecriture qui permet d'eviter de passer getManager en paramètre du construct
            // $em = $this->getDoctrine()->getManager();

            //signifie que les donnes sont à conserver
            $this->em->persist($event);
            //injection dans la BDD
            $this->em->flush();

            //message flash
            $this->addFlash('success', 'Votre évènement a bien été ajouté');

            //redirection sur la page du nouvel event si reussite du formulaire(recup id)
            return $this->redirectToRoute('event_display', array(
                'id' => $event->getId(),
            ));
        }



        return $this->render('event/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/{id}/join", name="join" ,requirements={"id"="\d+"}, methods={"POST"})
     *@IsGranted("ROLE_USER")
     */
    public function join($id): Response
    //avec AJAX-JS
    {
        //recup event
        $event = $this->eventService->getOne($id);
        //recup user
        $user = $this->getUser();
        //generation numero resa
        $bookingNumber = bin2hex(random_bytes(12));

        //insertion dans la BDD
        $participation = new Participation();
        $participation->setEvent($event);
        $participation->setUser($user);
        $participation->setBookingNumber($bookingNumber);

        $this->em->persist($participation);
        $this->em->flush($participation);

        return new JsonResponse(array(
            'status' => true
        ));
    }
}
