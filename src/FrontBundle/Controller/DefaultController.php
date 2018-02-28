<?php

namespace FrontBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function validerrdvAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();


            $idrdv=$request->get('id');


            $rdv = $em->getRepository('UserBundle:RDV')->find($idrdv);
            $rdv->setConfirme(1);


            $em = $this->getDoctrine()->getManager();
            $em->persist($rdv);
            $em->flush();
            $rdvconfirmed = $em->getRepository('UserBundle:RDV')->findAll();
            $response = $this->renderView('@BackOffice/template/ConfirmationRdv.html.twig', array('rdvall' => $rdvconfirmed));
            return new JsonResponse($response);
        }
        return new JsonResponse(array("status"=>true));
    }
    public function annulerrdvAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();


            $idrdv=$request->get('id');


            $rdv = $em->getRepository('UserBundle:RDV')->find($idrdv);
            $rdv->setConfirme(0);


            $em = $this->getDoctrine()->getManager();
            $em->persist($rdv);
            $em->flush();
            $rdvconfirmed = $em->getRepository('UserBundle:RDV')->findAll();
            $response = $this->renderView('@BackOffice/template/ConfirmationRdv.html.twig', array('rdvall' => $rdvconfirmed));
            return new JsonResponse($response);
        }
        return new JsonResponse(array("status"=>true));
    }
    public function annulerrdvuserAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();


            $idrdv=$request->get('id');


            $rdv = $em->getRepository('UserBundle:RDV')->find($idrdv);
            $rdv->setConfirme(0);


            $em = $this->getDoctrine()->getManager();
            $em->persist($rdv);
            $em->flush();
            $rdvconfirmed = $em->getRepository('UserBundle:RDV')->findAll();
            $response = $this->renderView('@BackOffice/template/rdvuser.html.twig', array('rdvall' => $rdvconfirmed));
            return new JsonResponse($response);
        }
        return new JsonResponse(array("status"=>true));
    }
    function pdfRDVAction(Request $request){
        $snapy=$this->get("knp_snappy.pdf");
        $snapy->setOption('encoding','UTF-8');

        $em = $this->getDoctrine()->getManager();
        $id=$request->get('id');
        $rdv = $em->getRepository('UserBundle:RDV')->find($id);


        $html=$this->renderView('FrontBundle:template:PDF.html.twig',array('rdv'=>$rdv));
        $filename= "rdvpdf";

        return new Response(
            $snapy->getOutputFromHtml($html),
            200,array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
    public function chartAction()
    {
        $em=$this->getDoctrine()->getManager();
//        $medecin = $em->getRepository('UserBundle:User')->findAll();

        $nbmednonvalides=$em->getRepository('UserBundle:Medecin')->medecinnonvalidesAction();
        $nbmedvalides=$em->getRepository('UserBundle:Medecin')->medecinUsersAction();
        $nbuserstotal=$em->getRepository('UserBundle:Medecin')->allusersAction();

        $pourcent=($nbmedvalides/($nbmedvalides+$nbmednonvalides))*100;
        $round=round($pourcent);

        // annonce
        $nbannoncestotal=$em->getRepository('UserBundle:Medecin')->annoncestotalsAction();
        $nbannoncesvalides=$em->getRepository('UserBundle:Medecin')->annoncesvalidesAction();
        $pourcentannonce=round(($nbannoncesvalides/$nbannoncestotal)*100);
        //rdv
        $nbrdvtotals=$em->getRepository('UserBundle:RDV')->RdvTotalAction();
        $nbrdvvalides=$em->getRepository('UserBundle:RDV')->RdvValidesAction();

        $pourcentrdv=round(($nbrdvvalides/$nbrdvtotals)*100);

        $pieChart = new PieChart();

        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Spécilité des médecins inscrits'],
                ['dentiste',     11],
                ['généraliste',      2],
                ['ORM',  2],
                ['dermatologue', 2],
                ['ophtalmologue',    7]
            ]
        );
        $pieChart->getOptions()->setTitle('Medecins par spécialité');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);



        return $this->render('@BackOffice/template/chart.html.twig',array('piechart' => $pieChart,'poucent'=>$round,
            'nbmedvalides'=>$nbmedvalides,'nbmednonvalides'=>$nbmednonvalides,'nbtotalusers'=>$nbuserstotal,'pourcentannonce'=>$pourcentannonce,
            'poucentagerdv'=>$pourcentrdv
        ));
    }
}
