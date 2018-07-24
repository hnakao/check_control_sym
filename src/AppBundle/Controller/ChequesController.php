<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cheques;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Cheque controller.
 *
 * @Route("cheques")
 */
class ChequesController extends Controller {

    /**
     * Lists all cheque entities.
     *
     * @Route("/", name="cheques_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cheques = $em->getRepository('AppBundle:Cheques')->findby(array('userId' => $user->getId()));

        $cheque = new Cheques();
        $form = $this->createForm('AppBundle\Form\ChequesType', $cheque);


        foreach ($cheques as $i => $cheque) {

            $AtDate = $cheque->getAtDate();
            $postDate = $cheque->getPostDate();
            $now = new \DateTime('now');
            $date = $AtDate;
            if ($postDate) {
                $date = $postDate;
            }
            $now->format('Y-m-d 00:00:00');

            if ($date) {
                $date->format('Y-m-d 00:00:00');
                $difference = $now->diff($date);
                $cheque->setDiasRestantes(intval($difference->format('%R%a')));
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($cheque);
            $em->flush();


//            print_r($user->getUsername());
            //print_r(intval(($difference->format('%R%a'))));
        }

        //return new JsonResponse(array('name' => 'name'));

        return $this->render('cheques/index.html.twig', array(
                    'cheques' => $cheques,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Lists all cheque entities.
     *
     * @Route("/at_date", name="at_date")
     * @Method("GET")
     */
    public function atDateAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userId = $user->getId();


        $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT p"
                . " FROM AppBundle:Cheques p"
                . " WHERE p.atDate!='' AND p.userId = " . $userId);

        $cheques = $query->getResult();
        //$cheques = $em->getRepository('AppBundle:Cheques')->findAll();

        $cheque = new Cheques();
        $form = $this->createForm('AppBundle\Form\ChequesType', $cheque);


        foreach ($cheques as $i => $cheque) {

            $AtDate = $cheque->getAtDate();
            $postDate = $cheque->getPostDate();
            $now = new \DateTime('now');
            $date = $AtDate;
            if ($postDate) {
                $date = $postDate;
            }
            $now->format('Y-m-d 00:00:00');

            if ($date) {
                $date->format('Y-m-d 00:00:00');
                $difference = $now->diff($date);
                $cheque->setDiasRestantes(intval($difference->format('%R%a')));
            }
        }

        return $this->render('cheques/at_date.html.twig', array(
                    'cheques' => $cheques,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Lists all cheque entities.
     *
     * @Route("/por_cobrar", name="por_cobrar")
     * @Method("GET")
     */
    public function porCobrarAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userId = $user->getId();


        $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT p"
                . " FROM AppBundle:Cheques p"
                . " WHERE p.atDate!='' AND p.pagado = 'no'");

        $cheques = $query->getResult();
        //$cheques = $em->getRepository('AppBundle:Cheques')->findAll();

        $cheque = new Cheques();
        $form = $this->createForm('AppBundle\Form\ChequesType', $cheque);


        foreach ($cheques as $i => $cheque) {

            $AtDate = $cheque->getAtDate();
            $postDate = $cheque->getPostDate();
            $now = new \DateTime('now');
            $date = $AtDate;
            if ($postDate) {
                $date = $postDate;
            }
            $now->format('Y-m-d 00:00:00');

            if ($date) {
                $date->format('Y-m-d 00:00:00');
                $difference = $now->diff($date);
                $cheque->setDiasRestantes(intval($difference->format('%R%a')));
            }
        }

        return $this->render('cheques/at_date.html.twig', array(
                    'cheques' => $cheques,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Lists all cheque entities.
     *
     * @Route("/buscar", name="buscar")
     * @Method("POST")
     */
    public function BuscarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userId = $user->getId();

        $data = $request->request->all();
        $starDate = $data['start'];
        $endDate = $data['end'];

        if ($starDate != "" && $endDate != "") {
            $query = $this->getDoctrine()->getManager()
                    ->createQuery("SELECT p"
                    . " FROM AppBundle:Cheques p"
                    . " WHERE p.atDate >='" . $starDate . "' AND p.atDate <='" . $endDate."'");
            
            
        } else if ($starDate != "" && $endDate == "") {
            $query = $this->getDoctrine()->getManager()
                    ->createQuery("SELECT p"
                    . " FROM AppBundle:Cheques p"
                    . " WHERE p.atDate >=" . $starDate);
        } else if ($starDate == "" && $endDate != "") {
            $query = $this->getDoctrine()->getManager()
                    ->createQuery("SELECT p"
                    . " FROM AppBundle:Cheques p"
                    . " WHERE p.atDate <=" . $endDate);
        }


        $cheques = $query->getResult();
        //$cheques = $em->getRepository('AppBundle:Cheques')->findAll();

        $cheque = new Cheques();
        $form = $this->createForm('AppBundle\Form\ChequesType', $cheque);


        foreach ($cheques as $i => $cheque) {

            $AtDate = $cheque->getAtDate();
            $postDate = $cheque->getPostDate();
            $now = new \DateTime('now');
            $date = $AtDate;
            if ($postDate) {
                $date = $postDate;
            }
            $now->format('Y-m-d 00:00:00');

            if ($date) {
                $date->format('Y-m-d 00:00:00');
                $difference = $now->diff($date);
                $cheque->setDiasRestantes(intval($difference->format('%R%a')));
            }
        }

        return $this->render('cheques/index.html.twig', array(
                    'cheques' => $cheques,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Lists all cheque entities.
     *
     * @Route("/cobrados", name="cobrados")
     * @Method("GET")
     */
    public function cobradosAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userId = $user->getId();


        $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT p"
                . " FROM AppBundle:Cheques p"
                . " WHERE p.pagado = 'si'");

        $cheques = $query->getResult();
        //$cheques = $em->getRepository('AppBundle:Cheques')->findAll();

        $cheque = new Cheques();
        $form = $this->createForm('AppBundle\Form\ChequesType', $cheque);


        foreach ($cheques as $i => $cheque) {

            $AtDate = $cheque->getAtDate();
            $postDate = $cheque->getPostDate();
            $now = new \DateTime('now');
            $date = $AtDate;
            if ($postDate) {
                $date = $postDate;
            }
            $now->format('Y-m-d 00:00:00');

            if ($date) {
                $date->format('Y-m-d 00:00:00');
                $difference = $now->diff($date);
                $cheque->setDiasRestantes(intval($difference->format('%R%a')));
            }
        }

        return $this->render('cheques/at_date.html.twig', array(
                    'cheques' => $cheques,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Lists all cheque entities.
     *
     * @Route("/post_date", name="post_date")
     * @Method("GET")
     */
    public function postDateAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userId = $user->getId();

        $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT p"
                . " FROM AppBundle:Cheques p"
                . " WHERE p.postDate!='' AND p.userId = " . $userId);

        $cheques = $query->getResult();

        $cheque = new Cheques();
        $form = $this->createForm('AppBundle\Form\ChequesType', $cheque);


        foreach ($cheques as $i => $cheque) {

            $AtDate = $cheque->getAtDate();
            $postDate = $cheque->getPostDate();
            $now = new \DateTime('now');
            $date = $AtDate;
            if ($postDate) {
                $date = $postDate;
            }
            $now->format('Y-m-d 00:00:00');

            if ($date) {
                $date->format('Y-m-d 00:00:00');
                $difference = $now->diff($date);
                $cheque->setDiasRestantes(intval($difference->format('%R%a')));
            }
        }

        return $this->render('cheques/post_date.html.twig', array(
                    'cheques' => $cheques,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new cheque entity.
     *
     * @Route("/new", name="cheques_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $cheque = new Cheques();
        $form = $this->createForm('AppBundle\Form\ChequesType', $cheque);
        $form->handleRequest($request);

//        $var = $request->query->get('appbundle_cheques[notes]');
//        var_dump("++++++++++++++++++++++++++++++++++++++++++++++");
//        $data = $request->request->all();
//        $name = $data['www'];
//        var_dump($name);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $cheque->setUserId($user);
            $em->persist($cheque);
            $em->flush();


            return $this->redirectToRoute('cheques_index');
        }


//        $response = new Response(json_encode(array('name' => "name")));
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;

        return $this->render('cheques/new.html.twig', array(
                    'cheque' => $cheque,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cheque entity.
     *
     * @Route("/{id}", name="cheques_show")
     * @Method("GET")
     */
    public function showAction(Cheques $cheque) {
        $deleteForm = $this->createDeleteForm($cheque);

        return $this->render('cheques/show.html.twig', array(
                    'cheque' => $cheque,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cheque entity.
     *
     * @Route("/{id}/edit", name="cheques_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cheques $cheque) {
        $deleteForm = $this->createDeleteForm($cheque);
        $editForm = $this->createForm('AppBundle\Form\ChequesType', $cheque);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cheques_index');
            // return $this->redirectToRoute('cheques_edit', array('id' => $cheque->getId()));
        }

        return $this->render('cheques/edit.html.twig', array(
                    'cheque' => $cheque,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cheque entity.
     *
     * @Route("/{id}", name="cheques_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cheques $cheque) {
        $form = $this->createDeleteForm($cheque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cheque);
            $em->flush();
        }

        return $this->redirectToRoute('cheques_index');
    }

    /**
     * Creates a form to delete a cheque entity.
     *
     * @param Cheques $cheque The cheque entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cheques $cheque) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('cheques_delete', array('id' => $cheque->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
