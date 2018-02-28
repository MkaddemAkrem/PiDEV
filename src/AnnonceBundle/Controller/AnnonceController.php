<?php

namespace AnnonceBundle\Controller;
use AnnonceBundle\Entity\Annonce;

use AnnonceBundle\Entity\commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\ExpressionLanguage\Tests\Node\Obj;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;
 use Swift_Message;
use AnnonceBundle\Entity\Mail;
use AnnonceBundle\Form\MailType;

/**
 * Evenement controller.
 *
 */
class AnnonceController extends Controller
{
    /**
     * Lists all annonces entities.
     *
     */
    public function indexAction(Request $request)
    {  $em = $this->getDoctrine()->getManager();
       $user=$this->getUser();
       $x=$user->getId();
       $moi=$em->getRepository('UserBundle:User')->find($x);


       // $annonces = $em->getRepository('AnnonceBundle:Annonce')->findAll();

        $query =$em->createQuery("SELECT annonce FROM AnnonceBundle:Annonce annonce WHERE annonce.confirmation like :confirmation")
            ->setParameter('confirmation', '%0%');
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator  = $this->get('knp_paginator');
        $result= $paginator->paginate(
            $query  ,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
            );
//        $result=$em->getRepository(Annonce::class)->findAll();


        return $this->render(':annonce:index.html.twig', array(
            'annonces' => $result,'ys'=>$moi
        ));
    }



    public function validerAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $an = $em->getRepository(Annonce::class)->validerannonceAction($id);
            $an->execute();

            $annonceaffiche=$em->getRepository(Annonce::class)->findAll();

            $response = $this->renderView('annonce/validerannonce.html.twig', array('all' => $annonceaffiche,'id'=>$id));
            return new JsonResponse($response);
        }
        return new JsonResponse(array("status"=>true));

    }

    public function bloqueAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $an = $em->getRepository(Annonce::class)->bloqueannonceActionn($id);
            $an->execute();

            $annonceaffiche=$em->getRepository(Annonce::class)->findAll();

            $response = $this->renderView('annonce/validerannonce.html.twig', array('all' => $annonceaffiche,'id'=>$id));
            return new JsonResponse($response);
        }
        return new JsonResponse(array("status"=>true));

    }
    /**
     * Finds and displays a annonce entity.
     *
     */
    public function showAction(Annonce $annonce)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($annonce);
        $user=$this->getUser();
        $x=$user->getId();
        $moi=$em->getRepository('UserBundle:User')->find($x);
        $com = new commentaire();
        $commentaire=$em->getRepository('AnnonceBundle:commentaire')->findAll();
        return $this->render(':annonce:show.html.twig', array(
            'annonce' => $annonce,
            'delete_form' => $deleteForm->createView(),
        'ys'=>$moi,'commentaires'=>$commentaire,
        ));
    }

    public function afficheAnnonceAction(){


        $em = $this->getDoctrine()->getManager();
            $annonce = $em->getRepository(Annonce::class)->findAll();
        $annoncesv=$em->getRepository(Annonce::class)->afficheAnnonceValides();


        return $this->render('@BackOffice/template/table.html.twig', array('annonce'=>$annonce,'annoncessv'=>$annoncesv));

    }
    /**
     * Creates a new evenement entity.
     *
     */
    public function newAction(Request $request)
    {
        $annonce = new Annonce();
        $mail = new Mail();
        $form = $this->createForm('AnnonceBundle\Form\AnnonceType', $annonce);
        $form->handleRequest($request);
        $user = $this->getUser();
        $id = $user->getId();





        if ($form->isSubmitted() && $form->isValid()) {
            $file = $annonce->getImage();
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $annonce->setUtilisateur($user);
            $annonce->setConfirmation(1);
            $date= new \DateTime();
            $annonce->setCreationDate($date);

            // Move the file to the directory where brochures are stored
            $path = "C:/Users/wamp64/www/projetdev/web" ;
            $file->move(
                $path,
                $fileName
            );
            $annonce->setImage($fileName);


            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            $message = \Swift_Message::newInstance()
                ->setSubject('Veulliez acceptez lannonce de :')
                ->setFrom($user->getEmail(),$annonce->getDescription())
                ->setTo('sahti@sahti.tn')
                ->setBody(
                    $this->renderView(
                        'annonce/email.html.twig'
                    ),
                    'text/html'
                );

            $this->get('mailer')->send($message);

            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/new.html.twig', array(
            'annonce' => $annonce,
            'form' => $form->createView(),
        ));
    }


    public function chercherAction(Request $request)
    {

        {
            if($request->isXmlHttpRequest() && $request->isMethod('post')){

                $em = $this->getDoctrine()->getEntityManager();
                $user=$this->getUser();
                $x=$user->getId();
                $moi=$em->getRepository('UserBundle:User')->find($x);
                $titre =$request->get('titre');

                $query =$em->getRepository('AnnonceBundle:Annonce')->createQueryBuilder('u');

                $query =$em->createQuery("SELECT annonce FROM AnnonceBundle:Annonce annonce WHERE annonce.confirmation like :confirmation AND annonce.titre LIKE :titre ")
                    ->setParameter('titre','%'.$titre.'%')
                    ->setParameter('confirmation', '%0%');

                /**
                 * @var $paginator \Knp\Component\Pager\Paginator
                 */

                $paginator  = $this->get('knp_paginator');
                $result= $paginator->paginate(
                    $query  ,
                    $request->query->getInt('page',1),
                    $request->query->getInt('limit',5)

                );

                $response = $this->renderView('annonce/search.html.twig',array('all'=>$result,'ys'=>$moi));
                return  new JsonResponse($response) ;
            }
            return new JsonResponse(array("status"=>true));




        }

    }
    public function MailAction(Request $request)
    {

        $mail = new Mail();
        $user = $this->getUser();
        $id = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle\Entity\User")->find($id);
        $form=  $this->createForm(MailType::class,  $mail);
        $form->handleRequest($request) ;
        if ($form->isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Passation de la commande pour :')
                ->setFrom($user->getEmail(),$mail->getText())
                ->setTo('sahti@sahti.tn')
                ->setBody(
                    $this->renderView(
                        'annonce/email.html.twig'
                    ),
                    'text/html'
                );

            $this->get('mailer')->send($message);
            return $this->redirect($this->generateUrl('my_app_mail_accuse'));         }

        return $this->render(':annonce:indexMail.html.twig', array('form'=>$form->createView()));
    }


    public function successAction()
    {
        return new Response("email envoyé avec succès, Merci de vérifier votre boite mail.");
    }

    /**
     * Displays a form to edit an existing annonce entity.
     *
     */
    public function editAction(Request $request, Annonce $annonce)
    {
        $deleteForm = $this->createDeleteForm($annonce);
        $editForm = $this->createForm('AnnonceBundle\Form\AnnonceType', $annonce);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $annonce->getImage();
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $path = "C:/Users/wamp64/www/projetdev/web" ;
            $file->move(
                $path,
                $fileName
            );
            $annonce->setImage($fileName);


            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('annonce_index');
        }


        return $this->render('annonce/edit.html.twig', array(
            'annonce' => $annonce,
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
        $annonce=$em->getRepository(Annonce::class)->find($id);
        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute('annonce_index');


    }

    /**
     * Creates a form to delete a evenement entity.
     *
     * @param Annonce $evenement The evenement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Annonce $annonce)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('annonce_delete', array('id' => $annonce->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
