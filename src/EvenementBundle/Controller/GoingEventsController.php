<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\GoingEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GoingEventsController extends Controller
{
    public function checkVisitedAjaxAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $eventId = $request->get('id');
            $user = $this->getUser();


            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository("EvenementBundle:Evenement")->find($eventId);
            $favourite = $em->getRepository("EvenementBundle:GoingEvents")->isGoing($event, $user);
            $response = array('Going' => true);
            if (count($favourite) == 0) {
                $response = array('Going' => false);
            }


            return new JsonResponse($response);
        }

        return new JsonResponse();
    }


    public function deleteGoingAjaxAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EvenementBundle:Evenement")->find($id);
        $going = $em->getRepository("EvenementBundle:GoingEvents")->findOneBy(array('event' => $event, 'user' => $user));
        $em->remove($going);
       $event->setNbrGoing($event->getNbrGoing()-1);
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('evenement_show', array('id' => $id));
    }

    public function addGoingAjaxAction(Request $request, $id)
    {
        $user = $this->getUser();
        $id_user = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EvenementBundle:Evenement")->find($id);
        $exist = $em->getRepository("EvenementBundle:GoingEvents")->countGoingEvent($event, $user);
        $exist_interet = $em->getRepository("EvenementBundle:InterestedEvents")->countInterestEvent($event, $user);
        if ($exist_interet == 1) {
            $interested = $em->getRepository("EvenementBundle:InterestedEvents")->findOneBy(array('event' => $event, 'user' => $user));
            $em->remove($interested);
            $event->setNbrInterested($event->getNbrInterested()-1);
            $em->flush();
        }
        if ($exist == 0) {



                    $going = new GoingEvents();
                    $going->setUser($user);
                    $going->setEvent($event);
                    $event->setNbrGoing($event->getNbrGoing()+1);
                    $em->persist($event);
                    $em->persist($going);
                    $em->flush();

        }
        $response = array('Going' => true);


        return $this->redirectToRoute('evenement_show', array('id' => $id));
    }



}
