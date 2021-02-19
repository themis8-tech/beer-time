<?php
namespace App\Service;

use DateTime;
use App\Repository\EventRepository;
use App\Repository\ParticipationRepository;

class EventService
{
   // précedemment dans EventController
   private $repository;
   private $participationRepository;

   public function __construct( EventRepository $repository, ParticipationRepository $participationRepository )
    {
        $this->repository = $repository;
        $this->participationRepository = $participationRepository;
    }


   // a injecter dans la methode list de EventController
   public function buildResult($query, $sort)
    {
        return $this->repository->search( $query, $sort );
    }

   // a injecter dans la methode display de EventController
   public function getOne($id)
   {
      return $this->repository->find($id);
   }

   public function countParticipant( $event )
    {
        return $this->participationRepository->count(array(
            // on compte le nombre de participant à un event->injecté method display du ctrl
            'event' => $event,
        ));
    }
}
