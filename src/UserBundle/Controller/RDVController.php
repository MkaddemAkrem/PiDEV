<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\RDV;

class RDVController extends Controller
{
    public function RDVAction(Request $request)
    {

        $rdv = new RDV();
        $em = $this->getDoctrine()->getManager();

        $iduser=$request->get('u') ;
        $user = $em->getRepository('UserBundle:User')->find($iduser);

        $idmed=$request->get('m') ;
        $medecin = $em->getRepository('UserBundle:Medecin')->find($idmed);

        $rdv->setUtilisateur($user);
        $rdv->setMedecin($medecin);
        $rdv->setDate( $request->get('date'));
        $rdv->setHeure($request->get('time'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($rdv);
        $em->flush();

//        $ser=new Serializer(array(new ObjectNormalizer()));
//        $data=$ser->normalize($rdv);
//        return new JsonResponse($data);

        return $this->redirectToRoute('user_homepage');
    }

}
