<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Url;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Url controller.
 */
class UrlController extends Controller
{
    /**
     * Lists all url entities.
     *
     * @Route("/", name="url_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $urls = $em->getRepository('AppBundle:Url')->findAll();

        return $this->render('url/index.html.twig', array(
            'urls' => $urls,
        ));
    }

    /**
     * Creates a new url entity.
     *
     * @Route("/new", name="url_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $url = new Url();
        $form = $this->createForm('AppBundle\Form\UrlType', $url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($url);
            $em->flush();

            return $this->redirectToRoute('url_show', array('id' => $url->getId()));
        }

        return $this->render('url/new.html.twig', array(
            'url' => $url,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a url entity.
     *
     * @Route("/{id}", name="url_show")
     * @Method("GET")
     */
    public function showAction(Url $url)
    {
        $deleteForm = $this->createDeleteForm($url);

        return $this->render('url/show.html.twig', array(
            'url' => $url,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing url entity.
     *
     * @Route("/{id}/edit", name="url_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Url $url)
    {
        $deleteForm = $this->createDeleteForm($url);
        $editForm = $this->createForm('AppBundle\Form\UrlType', $url);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('url_edit', array('id' => $url->getId()));
        }

        return $this->render('url/edit.html.twig', array(
            'url' => $url,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a url entity.
     *
     * @Route("/{id}", name="url_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Url $url)
    {
        $form = $this->createDeleteForm($url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($url);
            $em->flush();
        }

        return $this->redirectToRoute('url_index');
    }

    /**
     * Creates a form to delete a url entity.
     *
     * @param Url $url The url entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Url $url)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('url_delete', array('id' => $url->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
