<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 */
class CategoriesController extends Controller
{
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcommerceBundle:Categories')->findAll();

        return $this->render('EcommerceBundle:Default/Categories/ModulesUsed:menu.html.twig', array('categories' => $categories));
    }

    /**
     * Lists all category entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('EcommerceBundle:Categories')->findAll();

        return $this->render('@Ecommerce/Administration/categories/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     */
    public function newAction(Request $request)
    {
        $categories = new Categories();
        $form = $this->createForm('Ecommerce\EcommerceBundle\Form\CategoriesType', $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();

            return $this->redirectToRoute('Admincategories_show', array('id' => $categories->getId()));
        }

        return $this->render('@Ecommerce/Administration/categories/new.html.twig', array(
            'category' => $categories,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     */
    public function showAction(Categories $categories)
    {
        $deleteForm = $this->createDeleteForm($categories);

        return $this->render('@Ecommerce/Administration/categories/show.html.twig', array(
            'category' => $categories,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     */
    public function editAction(Request $request, Categories $categories)
    {
        $deleteForm = $this->createDeleteForm($categories);
        $editForm = $this->createForm('Ecommerce\EcommerceBundle\Form\CategoriesType', $categories);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('Admincategories_edit', array('id' => $categories->getId()));
        }

        return $this->render('@Ecommerce/Administration/categories/edit.html.twig', array(
            'category' => $categories,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction(Request $request, Categories $categories)
    {
        $form = $this->createDeleteForm($categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categories);
            $em->flush();
        }

        return $this->redirectToRoute('Admincategories_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Categories $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categories $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Admincategories_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
