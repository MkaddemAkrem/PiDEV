<?php

namespace MedicamentBundle\Controller;

use MedicamentBundle\Entity\Medicament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Medicament controller.
 *
 */
class MedicamentController extends Controller
{
    /**
     * Lists all medicament entities.
     *
     */
    public  function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $medicaments = $em->getRepository('MedicamentBundle:Medicament')->findAll();

        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $medicaments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 2)
        );
        return $this->render('medicament/index.html.twig', array(
            'medicaments' => $result,
        ));
    }
    public function ajoutAction(Request $request)
    {
        $user = $this->getUser();
        if($request->isXmlHttpRequest() && $request->isMethod('post')) {
        $medicament=new Medicament();


        $nom=$request->get('nom');
        $type=$request->get('type');
        $notice=$request->get('notice');
        $prix=$request->get('prix');


        $em=$this->getDoctrine()->getManager();
//        $medicament=$em->getRepository( Medicament::class)->findAll();
        $medicament->setNom($nom);
        $medicament->setType($type);
        $medicament->setPrix($prix);
        $medicament->setNotice($notice);
        $medicament->setUtilisateur($user);
        $em->persist($medicament);
        $em->flush();

        $medaffiche = $em->getRepository('MedicamentBundle\Entity\Medicament')->findAll();
        $response = $this->renderView('medicament/indexmedi.html.twig',array('all'=>$medaffiche));
        return  new JsonResponse($response) ;
        }

        return new JsonResponse(array("status"=>true));

    }


    /**
     * Creates a new medicament entity.
     *
     */
    public function newAction(Request $request)
    {
        $medicament = new Medicament();
        $form = $this->createForm('MedicamentBundle\Form\MedicamentType', $medicament);
        $form->handleRequest($request);
        $user = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $medicament->getImage();
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $medicament->setUtilisateur($user);

            // Move the file to the directory where brochures are stored
            $path = "C:/Users/wamp64/www/projetdev/web" ;
            $file->move(
                $path,
                $fileName
            );
            $medicament->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($medicament);
            $em->flush();

            return $this->redirectToRoute('medicament_index');
        }

        return $this->render('medicament/new.html.twig', array(
            'medicament' => $medicament,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a medicament entity.
     *
     */
    public function showAction(Medicament $medicament)
    {
        $deleteForm = $this->createDeleteForm($medicament);

        return $this->render('medicament/show.html.twig', array(
            'medicament' => $medicament,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing evenement entity.
     *
     */
    public function editAction(Request $request, Medicament $medicament)
    {
        $deleteForm = $this->createDeleteForm($medicament);
        $editForm = $this->createForm('MedicamentBundle\Form\MedicamentType', $medicament);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {



            $em = $this->getDoctrine()->getManager();
            $em->persist($medicament);
            $em->flush();

            return $this->redirectToRoute('medicament_index');
        }


        return $this->render('medicament/edit.html.twig', array(
            'medicament' => $medicament,
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
        $medicament=$em->getRepository(Medicament::class)->find($id);
        $em->remove($medicament);
        $em->flush();
        return $this->redirectToRoute('medicament_index');


    }

    /**
     * Creates a form to delete a medicament entity.
     *
     * @param Medicament $medicament The medicament entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Medicament $medicament)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('medicament_delete', array('id' => $medicament->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
