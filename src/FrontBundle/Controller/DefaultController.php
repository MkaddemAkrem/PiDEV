<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\Medecin;
use UserBundle\Entity\RDV;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('@Front/template/index.html.twig');
    }
    public function profileAction(Request $request)
    {
        $id=$request->get('id');

        $em = $this->getDoctrine()->getManager();
        $rdv = $em->getRepository('UserBundle:RDV')->findAll();
        $usera = $em->getRepository('UserBundle:User')->find($id);
        return $this->render('@Front/template/profile.html.twig',array(
            'rdv'=>$rdv,'users'=>$usera
        ));

    }

    public function profilemedAction(Request $request)
    {
        $id=$request->get('id');

        $em = $this->getDoctrine()->getManager();
        $medecinsvalides = $em->getRepository('UserBundle:User')->find($id);
        $rdv = $em->getRepository('UserBundle:RDV')->findAll();
        return $this->render('@Front/template/profilemedecin.html.twig',array(
            'rdvmedecins'=>$rdv,
            'medecinsvalides'=>$medecinsvalides ));
    }
    public function MedecinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $medecins = $em->getRepository('UserBundle:Medecin')->findAll();
        $medecinsvalides = $em->getRepository('UserBundle:Medecin')->afficheMedecinValides();


       $allMedecin=  array_merge($medecins,$medecinsvalides);
//
        $paginator  = $this->get('knp_paginator');
        $paginationMedicin = $paginator->paginate(
            $allMedecin, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );


        return $this->render('@Front/template/doctors-three-columns.html.twig', array(
            'medecins' => $paginationMedicin,
        ));
    }
    public function rechercherAction(Request $request)
    {
//
        if($request->isXmlHttpRequest() && $request->isMethod('post')){

            $titre =$request->get('titre');
            $em = $this->getDoctrine()->getEntityManager();
            $query =$em->getRepository('UserBundle:Medecin')->createQueryBuilder('u');
            $medecinsrech= $query->where($query->expr()->like('u.nom',':p'))
                ->setParameter('p','%'.$titre.'%')
                ->getQuery()->getResult();

            $response = $this->renderView('@Front/template/search.html.twig',array('all'=>$medecinsrech));
            return  new JsonResponse($response) ;
        }
        return new JsonResponse(array("status"=>true));

    }

    public function rechercherSpecAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $request->isMethod('post')){

            $titre =$request->get('spec');
            $em = $this->getDoctrine()->getEntityManager();
            $query =$em->getRepository('UserBundle:Medecin')->createQueryBuilder('u');
            $medecinsrech= $query->where($query->expr()->like('u.specialite',':p'))
                ->setParameter('p',$titre)
                ->getQuery()->getResult();

            $response = $this->renderView('@Front/template/searchspec.html.twig',array('all'=>$medecinsrech));
            return  new JsonResponse($response) ;
        }
        return new JsonResponse(array("status"=>true));
    }
    public function MedecinHomeAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();

        $medecins = $em->getRepository('UserBundle:Medecin')->find($id);

        $medecinsvalides = $em->getRepository('UserBundle:User')->find($id);
        if ($medecinsvalides instanceof User) {
            return $this->render('@Front/template/MedecinValideHomepage.html.twig', array(
              'id' => $id, 'medecinsvalides' => $medecinsvalides
            ));
        }
        else
            return $this->render('@Front/template/medecinHomepage.html.twig', array(
                'medecinshome' => $medecins, 'id' => $id
            ));

    }
//


    public function DeleteRdvAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $rdv=$em->getRepository(RDV::class)->find($id);


        $em->remove($rdv);
        $em->flush();


        return $this->redirectToRoute('homepage');
    }
    public function updaterdvAction(Request $request)
    {

        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();


            $idrdv=$request->get('idrdv');


            $rdv = $em->getRepository('UserBundle:RDV')->find($idrdv);
            $rdv->setDate($request->get('date'));
            $rdv->setHeure($request->get('time'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($rdv);
            $em->flush();
            $rdvall = $em->getRepository('UserBundle:RDV')->findAll();
            $response = $this->renderView('@Front/template/updateRdvAjax.html.twig', array('rdvall' => $rdvall));
            return new JsonResponse($response);
        }
        return new JsonResponse(array("status"=>true));
    }
}
