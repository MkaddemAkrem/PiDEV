<?php

namespace EvenementBundle\Controller;

use blackknight467\StarRatingBundle\Form\RatingType;
use Doctrine\DBAL\Schema\View;
use EvenementBundle\Entity\Evenement;
use EvenementBundle\Entity\GoingEvents;
use EvenementBundle\Entity\Rating;
use EvenementBundle\Entity\InterestedEvents;
use EvenementBundle\EvenementBundle;
use EvenementBundle\Form\RateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;
/**
 * Evenement controller.
 *
 */
class EvenementController extends Controller
{
    /**
     * Lists all evenement entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('EvenementBundle:Evenement')->findAll();
        $rating = $em->getRepository('EvenementBundle:Rating')->findAll();

        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $evenements,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 2)



        );



        $rating = $em->getRepository('EvenementBundle:Rating')->AVGRating();



        return $this->render('evenement/index.html.twig', array(
            'evenements' => $result,
            'rating' => $rating
        ));
    }

    public function indexmembreAction()
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('EvenementBundle:Evenement')->findAll();

        return $this->render('evenement/indexmembre.html.twig', array(
            'evenements' => $evenements,
        ));
    }




    /**
     * Creates a new evenement entity.
     *
     */






    public function newAction(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm('EvenementBundle\Form\EvenementType', $evenement);
        $form->handleRequest($request);
        $user = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $evenement->getImage();
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $evenement->setUtilisateur($user);

            // Move the file to the directory where brochures are stored
            $path = "C:/Users/wamp64/www/projetdev/web" ;
            $file->move(
                $path,
                $fileName
            );
            $evenement->setImage($fileName);
            $evenement->setNbrInterested(0);
            $evenement->setNbrGoing(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/new.html.twig', array(
            'evenement' => $evenement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a evenement entity.
     *
     */





public function showAction(Evenement $evenement, Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $deleteForm = $this->createDeleteForm($evenement);
        $id=$request->get('id');
        $Evenements = $em->getRepository('EvenementBundle:Evenement')->findAll();
        $Evenement=$em->getRepository("EvenementBundle:Evenement")->find($id);







        $lat=$Evenement->getLat();
        $lng=$Evenement->getLng();
        $exist_interet = $em->getRepository("EvenementBundle:InterestedEvents")->countInterestEvent($evenement, $user);
        $exist_going = $em->getRepository("EvenementBundle:GoingEvents")->countGoingEvent($evenement, $user);











        //$evenement=$em->getRepository()->find($id);
        $mark = $em->getRepository('EvenementBundle:Evenement')->find($id);
        $rating = $m->getRepository('EvenementBundle:Rating')->AVGRating();
        $rating=new rating();


        $form=$this->createFormBuilder($rating)
            ->add('rating', RatingType::class, [
                'label' => 'Rating'
            ])
            ->add('valider',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-xs btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setEvenement($mark->getId());
            $em->persist($rating);
            $em->flush();
        }








return $this->render('evenement/show.html.twig', array(
            'evenement' => $evenement,
            'delete_form' => $deleteForm->createView(),
            'latitude' => $lat,
            'longitude' => $lng,
'mark' => $mark,'form'=> $form->createView(),'rating'=>$rating,



            'exist_interet'=>$exist_interet==0,
            'exist_going'=>$exist_going==0
        ));
    }

//    }

    public function calculerVote(evenement $evenement, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $x = 0.0;
        $rates = $em->getRepository('EvenementBundle:Rate')->findBy(array('id_evenement' => $evenement->getId()));
        foreach ($rates as $rate) {
            $x = $x + $rate->getValue();
        }
        if (count($rates) > 0) {
            $x = $x / count($rates);
        } else {
            $x = 0;
        }
        return round($x);
    }










    public function pdfAction()
    {   $em = $this->getDoctrine()->getManager();
        $evenement =$em->getRepository('EvenementBundle:Evenement')->findAll();
        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('evenement/invitation.html.twig', array('evenement'=>$evenement
            //..Send some data to your view if you need to //
        ));

        $filename = 'moninvitation';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }


    /**
     * Displays a form to edit an existing evenement entity.
     *
     */
    public function editAction(Request $request, Evenement $evenement)
    {
        $deleteForm = $this->createDeleteForm($evenement);
        $editForm = $this->createForm('EvenementBundle\Form\EvenementType', $evenement);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $evenement->getImage();
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $path = "C:/Users/wamp64/www/projetdev/web" ;
            $file->move(
                $path,
                $fileName
            );
            $evenement->setImage($fileName);


            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('evenement_index');
        }


        return $this->render('evenement/edit.html.twig', array(
            'evenement' => $evenement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a evenement entity.
     *
     */
    public function deleteAction($id)
    {



        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository(Evenement::class)->find($id);
        $event=$em->getRepository(Evenement::class)->find($id);
        $interested=$em->getRepository(InterestedEvents::class)->findBy(array('event'=>$event));
        foreach
            ($interested as $in){
            $em->remove($in);
            $em->flush();
            }


        $going=$em->getRepository(GoingEvents::class)->findBy(array('event'=>$event));
        foreach
        ($going as $in){
            $em->remove($in);
            $em->flush();
        }
        $em->remove($evenement);
        $em->flush();

        return $this->redirectToRoute('evenement_index');


    }
    public function chercherAction(Request $request)
    {

        if($request->isXmlHttpRequest() && $request->isMethod('post')){

            $titre =$request->get('titre');
            $em = $this->getDoctrine()->getEntityManager();
            $query =$em->getRepository('EvenementBundle:Evenement')->createQueryBuilder('u');
            $evenement= $query->where($query->expr()->like('u.titre',':p'))
                ->setParameter('p','%'.$titre.'%')
                ->getQuery()->getResult();

            $response = $this->renderView('evenement/search.html.twig',array('all'=>$evenement));
            return  new JsonResponse($response) ;
        }
        return new JsonResponse(array("status"=>true));




    }

    /**
     * Creates a form to delete a evenement entity.
     *
     * @param Evenement $evenement The evenement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Evenement $evenement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('evenement_delete', array('id' => $evenement->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}
