<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Programador;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Programador controller.
 *
 * @Route("programador")
 */
class ProgramadorController extends Controller
{
    /**
     * Lists all programador entities.
     *
     * @Route("/", name="programador_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $programadors = $em->getRepository('AppBundle:Programador')->findAll();

        return $this->render('programador/index.html.twig', array(
            'programadors' => $programadors,
        ));
    }

    /**
     * Creates a new programador entity.
     *
     * @Route("/new", name="programador_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $programador = new Programador();
        $form = $this->createForm('AppBundle\Form\ProgramadorType', $programador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($programador);
            $em->flush();

            return $this->redirectToRoute('programador_show', array('id' => $programador->getId()));
        }

        return $this->render('programador/new.html.twig', array(
            'programador' => $programador,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a programador entity.
     *
     * @Route("/{id}", name="programador_show")
     * @Method("GET")
     */
    public function showAction(Programador $programador)
    {
        $deleteForm = $this->createDeleteForm($programador);

        return $this->render('programador/show.html.twig', array(
            'programador' => $programador,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing programador entity.
     *
     * @Route("/{id}/edit", name="programador_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Programador $programador)
    {
        $deleteForm = $this->createDeleteForm($programador);
        $editForm = $this->createForm('AppBundle\Form\ProgramadorType', $programador);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('programador_edit', array('id' => $programador->getId()));
        }

        return $this->render('programador/edit.html.twig', array(
            'programador' => $programador,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a programador entity.
     *
     * @Route("/{id}", name="programador_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Programador $programador)
    {
        $form = $this->createDeleteForm($programador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($programador);
            $em->flush();
        }

        return $this->redirectToRoute('programador_index');
    }

    /**
     * Creates a form to delete a programador entity.
     *
     * @param Programador $programador The programador entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Programador $programador)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('programador_delete', array('id' => $programador->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
