<?php

namespace TSEP\Bundle\AdminBundle\Controller;

use Xaav\QueueBundle\JobQueue\Provider\FlatFileProvider;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use TSEP\Bundle\AdminBundle\Job\ProcessIndexingRequestJob;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TSEP\Bundle\SearchBundle\Entity\Profile;
use TSEP\Bundle\AdminBundle\Form\ProfileType;

/**
 * Profile controller.
 *
 * @Route("/admin/profile")
 */
class ProfileController extends Controller
{
    /**
     * Lists all Profile entities.
     *
     * @Route("/", name="admin_profile")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TSEPSearchBundle:Profile')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Profile entity.
     *
     * @Route("/{id}/show", name="admin_profile_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TSEPSearchBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Profile entity.
     *
     * @Route("/new", name="admin_profile_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Profile();
        $form   = $this->createForm(new ProfileType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Profile entity.
     *
     * @Route("/create", name="admin_profile_create")
     * @Method("post")
     * @Template("TSEPAdminBundle:Profile:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Profile();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProfileType(), $entity);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_profile_show', array('id' => $entity->getId())));

            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Profile entity.
     *
     * @Route("/{id}/edit", name="admin_profile_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TSEPSearchBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        $editForm = $this->createForm(new ProfileType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Profile entity.
     *
     * @Route("/{id}/update", name="admin_profile_update")
     * @Method("post")
     * @Template("TSEPAdminBundle:Profile:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TSEPSearchBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        $editForm   = $this->createForm(new ProfileType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $editForm->bindRequest($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_profile_edit', array('id' => $id)));
            }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Profile entity.
     *
     * @Route("/{id}/delete", name="admin_profile_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository('TSEPSearchBundle:Profile')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Profile entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('admin_profile'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * @Route("/{id}/index", name="admin_profile_startIndexer")
     */
    public function startIndexerAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $profile = $em->getRepository('TSEPSearchBundle:Profile')->findOneById($id);
        $provider = new FlatFileProvider($this->get('kernel')->getCacheDir());
        $queue = $provider->getJobQueueByName('indexingRequests');
        $this->get('logger')->debug($profile);
        $queue->addJobToQueue(new ProcessIndexingRequestJob($profile));

        return new RedirectResponse($this->generateUrl('admin_processQueue', array('queue' => 'indexingRequests')));
    }
}
