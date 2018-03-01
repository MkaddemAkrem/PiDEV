<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Utilisateursadress controller.
 *
 */
class UtilisateursAdressesController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $utilisateursAdresses = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->findBy(array('utilisateur'=>$this->getUser()));

        return $this->render('@Ecommerce/Default/utilisateursadresses/index.html.twig', array(
            'utilisateursAdresses' => $utilisateursAdresses,
        ));
    }


    public function newAction(Request $request)
    {
        $utilisateur = $this->container->get('security.token_storage')->getToken()->getUser();
        $utilisateursAdresses = new UtilisateursAdresses();
        $form = $this->createForm('Ecommerce\EcommerceBundle\Form\UtilisateursAdressesType', $utilisateursAdresses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $utilisateursAdresses->setUtilisateur($utilisateur);
            $em->persist($utilisateursAdresses);
            $em->flush();

            return $this->redirectToRoute('utilisateursadresses_show', array('id' => $utilisateursAdresses->getId()));
        }

        return $this->render('@Ecommerce/Default/utilisateursadresses/new.html.twig', array(
            'utilisateursAdresses' => $utilisateursAdresses,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a utilisateursAdress entity.
     *
     */
    public function showAction(UtilisateursAdresses $utilisateursAdresses)
    {
        $deleteForm = $this->createDeleteForm($utilisateursAdresses);

        return $this->render('@Ecommerce/Default/utilisateursadresses/show.html.twig', array(
            'utilisateursAdresses' => $utilisateursAdresses,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing utilisateursAdress entity.
     *
     */
    public function editAction(Request $request, UtilisateursAdresses $utilisateursAdresses)
    {
        $deleteForm = $this->createDeleteForm($utilisateursAdresses);
        $editForm = $this->createForm('Ecommerce\EcommerceBundle\Form\UtilisateursAdressesType', $utilisateursAdresses);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateursadresses_edit', array('id' => $utilisateursAdresses->getId()));
        }

        return $this->render('@Ecommerce/Default/utilisateursadresses/edit.html.twig', array(
            'utilisateursAdresses' => $utilisateursAdresses,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a utilisateursAdress entity.
     *
     */
    public function deleteAction(Request $request, UtilisateursAdresses $utilisateursAdresses)
    {
        $form = $this->createDeleteForm($utilisateursAdresses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateursAdresses);
            $em->flush();
        }

        return $this->redirectToRoute('utilisateursadresses_index');
    }

    /**
     * Creates a form to delete a utilisateursAdress entity.
     *
     * @param UtilisateursAdresses $utilisateursAdress The utilisateursAdress entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UtilisateursAdresses $utilisateursAdresses)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('utilisateursadresses_delete', array('id' => $utilisateursAdresses->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
