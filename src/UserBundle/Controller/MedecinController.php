<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\Medecin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\RDV;
use UserBundle\Entity\User;
use UserBundle\UserBundle;


class MedecinController extends Controller
{


    public function newAction(Request $request)
    {
        $medecin = new Medecin();
        $form = $this->createForm('UserBundle\Form\MedecinType', $medecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($medecin);
            $em->flush();

            return $this->redirectToRoute('affiche_medecin', array('id' => $medecin->getId()));
        }

        return $this->render('@User/Medecin/formmedecin.html.twig', array(
            'medecin' => $medecin,
            'form' => $form->createView(),
        ));
    }



    public function showAction(Medecin $medecin)
    {
        $deleteForm = $this->createDeleteForm($medecin);

        return $this->render('medecin/show.html.twig', array(
            'medecin' => $medecin,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function editAction(Request $request)
    {
        $id = $request->get('id');
        if($request->isXmlHttpRequest() && $request->isMethod('POST')) {

            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $specialite = $request->get('specialite');
            $adresse = $request->get('adresse');

            $em = $this->getDoctrine()->getManager();
            $medecin = $em->getRepository(Medecin::class)->find($id);

            $medecin->setNom($nom);
            $medecin->setPrenom($prenom);
            $medecin->setSpecialite($specialite);
            $medecin->setAdresse($adresse);
            $em->flush();

            $medaffiche = $em->getRepository('UserBundle:Medecin')->findAll();
            $response = $this->renderView('@BackOffice/template/updateMedecin.html.twig',array('all'=>$medaffiche));
             return  new JsonResponse($response) ;

        }
        return new JsonResponse(array("status"=>true));
    }


    public function deleteAction(Request $request)
    {
            $id=$request->get('id');
            $em = $this->getDoctrine()->getManager();
            $medecin=$em->getRepository(Medecin::class)->find($id);

            $em->remove($medecin);
            $em->flush();

        $medaffiche = $em->getRepository('UserBundle:Medecin')->findAll();
        $response = $this->renderView('@BackOffice/template/updateMedecin.html.twig',array('all'=>$medaffiche));
        return  new JsonResponse($response) ;
    }


//    private function createDeleteForm(Medecin $medecin)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('medecin_delete', array('id' => $medecin->getId())))
//            ->setMethod('DELETE')
//            ->getForm()
//        ;
//    }
    public function afficheMedecinAction(){


            $em = $this->getDoctrine()->getManager();
            $medecin = $em->getRepository(Medecin::class)->findAll();

            $medecinsv=$em->getRepository(Medecin::class)->afficheMedecinValides();


        return $this->render('@BackOffice/template/widget.html.twig', array(
            'medecin'=>$medecin
        ,'medecinsv'=>$medecinsv));

    }

    public function ajaxDeleteAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->get('id');
        $medecin = $em->getRepository(Medecin::class)->find($id);
        if ($request->isMethod('POST')){
          $em->remove($medecin);
          $em->flush();
          return $this->redirectToRoute('affiche_medecin');
        }
        return $this->render('@BackOffice/template/deleteMedecin.html.twig',array('medecin'=>$medecin));
    }

    public function ajaxEditAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->get('id');
        $medecin = $em->getRepository(Medecin::class)->find($id);

        if ($request->isMethod('POST')){

            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $specialite = $request->get('specialite');
            $adresse = $request->get('adresse');
            $medecin->setNom($nom);
            $medecin->setPrenom($prenom);
            $medecin->setSpecialite($specialite);
            $medecin->setAdresse($adresse);
            $em=$this->getDoctrine()->getEntityManager();
            $em->persist($medecin);
            $em->flush();
            return $this->redirectToRoute('affiche_medecin') ;

        }

       return $this->render('@BackOffice/template/editMedecin.html.twig',array('medecin'=>$medecin));

    }

    public function bloquerAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $request->isMethod('POST')) {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();

        $med=$em->getRepository(Medecin::class)->BloquerMedecinAction($id);
        $med->execute();


        $medaffiche=$em->getRepository(Medecin::class)->afficheMedecinValides();

        $response = $this->renderView('@BackOffice/template/bloquerMedecin.html.twig',array('all'=>$medaffiche,'id'=>$id));
        return  new JsonResponse($response) ;
        }
        return new JsonResponse(array("status"=>true));
    }
    public function validerAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $request->isMethod('POST')) {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $med = $em->getRepository(Medecin::class)->validerMedAction($id);
        $med->execute();

        $medaffiche=$em->getRepository(Medecin::class)->afficheMedecinValides();

        $response = $this->renderView('@BackOffice/template/bloquerMedecin.html.twig', array('all' => $medaffiche,'id'=>$id));
        return new JsonResponse($response);
    }
        return new JsonResponse(array("status"=>true));

    }
}
