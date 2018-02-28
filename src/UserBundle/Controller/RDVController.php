<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\RDV;
use UserBundle\Entity\User;

class RDVController extends Controller
{
    public function RDVAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $rdv = new RDV();
            $em = $this->getDoctrine()->getManager();

            $iduser = $request->get('u');
            $user = $em->getRepository(User::class)->find($iduser);

            $idmed = $request->get('m');
            $medecin = $em->getRepository(User::class)->find($idmed);

            $rdv->setUtilisateur($user);
            $rdv->setMedecin($medecin);
            $rdv->setDate($request->get('date'));
            $rdv->setHeure($request->get('time'));
            $rdv->setConfirme(0);
            $rdv->setAnnule(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($rdv);
            $em->flush();

//        $ser=new Serializer(array(new ObjectNormalizer()));
//        $data=$ser->normalize($rdv);
//        return new JsonResponse($data);

            $response = $this->renderView('@Front/template/afterrdv.html.twig',array('rdv'=>$rdv));
            return new JsonResponse($response);
        }
        return new JsonResponse(array("status"=>true));
    }
}
