<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Evenement;
use MainBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EvenementController extends Controller
{
    public function AddAction(Request $request,$id){
        $event=new Evenement();
        $event->setNbrPersonnes(0);
        $event->setInteresses(0);
        $Form=$this->createForm(EvenementType::class,$event);
        $Form->handleRequest($request);
        if ($Form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $etab=$em->getRepository("MainBundle:Etablissement")->find($id);
            $event->setEtablissement($etab);
            $em->persist($event);
            $em->flush();
            $id_event=$event->getId();
            return $this->redirectToRoute('profile_event_user',array('id_event'=>$id_event));
        }
        return $this->render('MainBundle:Evenement:ajout.html.twig',array(
            'form'=>$Form->createView()
        ));


    }

    public function RemoveAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("MainBundle:Evenement")->find($id);
        $id_etab=$em->getRepository("MainBundle:Etablissement")->find($event->getEtablissement()->getId());
        $interested=$em->getRepository('MainBundle:InterestEvent')->findBy(array('event'=>$event));
        foreach ($interested as $in){
            $em->remove($in);
            $em->flush();
        }
        $going=$em->getRepository('MainBundle:GoingEvent')->findBy(array('event'=>$event));
        foreach ($going as $in){
            $em->remove($in);
            $em->flush();
        }
        $visitedEvent=$em->getRepository('MainBundle:VisitedEvent')->findBy(array('event'=>$event));
        foreach ($visitedEvent as $in){
            $em->remove($in);
            $em->flush();
        }
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('Afficher_Etablissement_Client',array('id'=>$id_etab->getId()));
    }
    public function RemoveAdminAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("MainBundle:Evenement")->find($id);
        $interested=$em->getRepository('MainBundle:InterestEvent')->findBy(array('event'=>$event));
        foreach ($interested as $in){
            $em->remove($in);
            $em->flush();
        }
        $going=$em->getRepository('MainBundle:GoingEvent')->findBy(array('event'=>$event));
        foreach ($going as $in){
            $em->remove($in);
            $em->flush();
        }
        $visitedEvent=$em->getRepository('MainBundle:VisitedEvent')->findBy(array('event'=>$event));
        foreach ($visitedEvent as $in){
            $em->remove($in);
            $em->flush();
        }
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('all_events_Admin');
    }
    public function AfficheAllAdminAction()
    {
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository("MainBundle:Evenement")->findAll();
        return $this->render('MainBundle:Admin:Affiche_Evenement.html.twig',
            array('even'=>$evenement));
    }


    public function AfficheAllUserAction()
    {
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("MainBundle:Evenement")->findRecent();
        return $this->render('MainBundle:Evenement:Liste.html.twig',
            array(
                'e'=>$event
            ));
    }
    public function AfficheEventsAdminIdEtabAction($id_etab)
    {
        $em=$this->getDoctrine()->getManager();
        $etab=$em->getRepository("MainBundle:Etablissement")->find($id_etab);
        $event=$em->getRepository("MainBundle:Evenement")->findBy(array('etablissement'=>$etab));
        return $this->render('MainBundle:Admin:Affiche_Evenement_Id_etab.html.twig',
            array(
                'even'=>$event
            ));
    }
    public function AfficheEventsUserIdEtabAction($id_etab)
    {
        $em=$this->getDoctrine()->getManager();
        $etab=$em->getRepository("MainBundle:Etablissement")->find($id_etab);
        $event=$em->getRepository("MainBundle:Evenement")->findBy(array('etablissement'=>$etab));
        return $this->render('MainBundle:Evenement:Liste_etab.html.twig',
            array(
                'even'=>$event,'etab'=>$etab
            ));
    }
    public function AfficheIdEventAdminAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository("MainBundle:Evenement")->find($id);
        return $this->render('MainBundle:Admin:Affiche_Evenement_Id.html.twig',
            array('even'=>$evenement));
    }

    public function AfficheIdEventUserAction($id_event)
    {
        $user=$this->getUser();
        $id_user=$user->getId();
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("MainBundle:Evenement")->find($id_event);
        $exist_interet = $em->getRepository("MainBundle:InterestEvent")->countInterestEvent($event, $user);
        $exist_going = $em->getRepository("MainBundle:GoingEvent")->countGoingEvent($event, $user);
        return $this->render('MainBundle:Evenement:afficher.html.twig',
            array(
                'e'=>$event,'exist_interet'=>$exist_interet==0,'exist_going'=>$exist_going==0
            ));
    }



    public function UpdateAction(Request $request,$id){
        $em=$this->getDoctrine()->getManager();
            $event=$em->getRepository("MainBundle:Evenement")->find($id);
        $Form=$this->createForm(EvenementType::class,$event);
        $Form->handleRequest($request);
        if ($Form->isValid() && $Form->isSubmitted()){
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('profile_event_user',array('id_event'=>$id));
        }
        return $this->render("MainBundle:Evenement:update.html.twig",
            array('form'=>$Form->createView()));
    }

}
