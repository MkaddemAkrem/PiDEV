<?php

namespace AnnonceBundle\Controller;

use AnnonceBundle\Entity\Annonce;
use AnnonceBundle\Entity\commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends Controller
{
    public function ajoutAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $request->isMethod('post')){
        $com = new commentaire();
        $em = $this->getDoctrine()->getManager();

        $commentaire=$request->get('com');
        $iduser=$request->get('u') ;
        $user = $em->getRepository('UserBundle:User')->find($iduser);
        $ida=$request->get('a') ;
        $annonce = $em->getRepository('AnnonceBundle:Annonce')->find($ida);
        $com->setUtilisateur($user);
        $com->setCommentaire($commentaire);
        $com->setAnnonce($annonce);

            $date= new \DateTime();
            $com->setCreationDatecom($date);
        $em = $this->getDoctrine()->getManager();
        $em->persist($com);
        $em->flush();


            $commentaire=$em->getRepository('AnnonceBundle:commentaire')->findAll();

//            $em = $this->getDoctrine()->getEntityManager();
//            $comm = $em->getRepository(commentaire::class)->findcomAction($ida);

$response = $this->renderView('annonce/ajoutcom.html.twig',array('commentaires'=>$commentaire,'ida'=>$ida));
return  new JsonResponse($response) ;
}
        return new JsonResponse(array("status"=>true));
    }

    public function editAction(Request $request)
    {


        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $id = $request->get('id');
            $commentaire = $request->get('com');


            $em = $this->getDoctrine()->getManager();
            $comm = $em->getRepository(commentaire::class)->find($id);
            $comm->setCommentaire($commentaire);

            $em->flush();

            $medaffiche = $em->getRepository('UserBundle:Medecin')->findAll();
            $response = $this->renderView('@BackOffice/template/updateMedecin.html.twig', array('all' => $medaffiche));
            return new JsonResponse($response);

        }

        return new JsonResponse(array("status" => true));
    }





public function afficheCommentaireAction(){


        $em = $this->getDoctrine()->getManager();
        $com = $em->getRepository(commentaire::class)->findAll();

//            $ser = new Serializer(array(new ObjectNormalizer()));
//            $data = $ser->normalize($medecin);

        return $this->render('annonce/show.html.twig', array('com'=>$com));

    }

}
