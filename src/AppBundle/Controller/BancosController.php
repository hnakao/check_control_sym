<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bancos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Banco controller.
 *
 * @Route("bancos")
 */
class BancosController extends Controller {

    /**
     * Lists all banco entities.
     *
     * @Route("/", name="bancos_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $bancos = $em->getRepository('AppBundle:Bancos')->findAll();
        $banco = new Bancos();
        $form = $this->createForm('AppBundle\Form\BancosType', $banco);

        return $this->render('bancos/index.html.twig', array(
                    'bancos' => $bancos,
                    'form' => $form->createView()
        ));
    }

    /**
     * Creates a new banco entity.
     *
     * @Route("/new", name="bancos_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $banco = new Bancos();
        $form = $this->createForm('AppBundle\Form\BancosType', $banco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $banco->getFotoPath();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            // moves the file to the directory where brochures are stored
            $file->move(
                    $this->getParameter('bancos_directory'), $fileName
            );

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $banco->setFotoPath($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($banco);
            $em->flush();

            return $this->redirectToRoute('bancos_index');
        }

        return $this->render('bancos/new.html.twig', array(
                    'banco' => $banco,
                    'form' => $form->createView(),
        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName() {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * Finds and displays a banco entity.
     *
     * @Route("/{id}", name="bancos_show")
     * @Method("GET")
     */
    public function showAction(Bancos $banco) {
        $deleteForm = $this->createDeleteForm($banco);

        return $this->render('bancos/show.html.twig', array(
                    'banco' => $banco,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing banco entity.
     *
     * @Route("/{id}/edit", name="bancos_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bancos $banco) {
        $deleteForm = $this->createDeleteForm($banco);
        $editForm = $this->createForm('AppBundle\Form\BancosType', $banco);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $banco->getFotoPath();
            echo $file;
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            // moves the file to the directory where brochures are stored
            $file->move(
                    $this->getParameter('bancos_directory'), $fileName
            );
            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $banco->setFotoPath($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($banco);
            $em->flush();
            //$this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bancos_index');
        }

        return $this->render('bancos/edit.html.twig', array(
                    'banco' => $banco,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a banco entity.
     *
     * @Route("/{id}", name="bancos_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bancos $banco) {
        $form = $this->createDeleteForm($banco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($banco);
            $em->flush();
        }

        return $this->redirectToRoute('bancos_index');
    }

    /**
     * Creates a form to delete a banco entity.
     *
     * @param Bancos $banco The banco entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bancos $banco) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('bancos_delete', array('id' => $banco->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}