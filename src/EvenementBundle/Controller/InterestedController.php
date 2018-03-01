<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\InterestedEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class InterestedController extends Controller
{
    public function checkInterestAjaxAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $eventId = $request->get('id');
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository("EvenementBundle:Evenement")->find($eventId);
            $favourite = $em->getRepository("EvenementBundle:InterestedEvents")->isInterested($event, $user);
            $response = array('interested' => true);
            if (count($favourite) == 0) {
                $response = array('interested' => false);
            }


            return new JsonResponse($response);
        }

        return new JsonResponse();
    }


    public function deleteInterestAjaxAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EvenementBundle:Evenement")->find($id);
        $exist_interet = $em->getRepository("EvenementBundle:InterestedEvents")->countInterestEvent($event, $user);
        if ($exist_interet == 1) {
            $intrest = $em->getRepository("EvenementBundle:InterestedEvents")->findOneBy(array('event' => $event, 'user' => $user));
            $em->remove($intrest);
            $event->setNbrInterested($event->getNbrInterested()-1);
            $em->persist($event);
            $em->flush();
        }
        return $this->redirectToRoute('evenement_show', array('id' => $id));
    }

    public function addInterestedAjaxAction(Request $request, $id)
    {
        $user = $this->getUser();
        $id_user = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EvenementBundle:Evenement")->find($id);
        $exist = $em->getRepository("EvenementBundle:InterestedEvents")->countInterestEvent($event, $user);
        $exist_going = $em->getRepository("EvenementBundle:GoingEvents")->countGoingEvent($event, $user);
        if ($exist_going ==1) {
            $going = $em->getRepository("EvenementBundle:GoingEvents")->findOneBy(array('user'=>$user,'event'=>$event));
            $em->remove($going);
            $event->setNbrGoing($event->getNbrGoing()-1);
            $em->flush();
        }
        if ($exist == 0) {
            $interest = new InterestedEvents();
            $interest->setUser($user);
            $interest->setEvent($event);
            $event->setNbrInterested($event->getNbrInterested()+1);
            $em->persist($interest);
            $em->persist($event);
            $em->flush();
        }

        $response = array('interested' => true);


        return $this->redirectToRoute('evenement_show', array('id' => $id));
    }


}
