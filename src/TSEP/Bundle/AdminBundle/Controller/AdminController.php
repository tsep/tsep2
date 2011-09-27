<?php

namespace TSEP\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Xaav\QueueBundle\JobQueue\Provider\FlatFileProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Admin Controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * The Dashboard.
     *
     * @Route("/", name="admin")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Processes the named queue through HTTP
     *
     * @Route("/{queue}/process", name="admin_processQueue")
     * @Template()
     */
    public function processQueueAction($queue)
    {
        if($this->getRequest()->isXmlHttpRequest()) {

            $provider = new FlatFileProvider($this->get('kernel')->getCacheDir());
            $jobqueue = $provider->getJobQueueByName($queue);
            $job = $jobqueue->getJobFromQueue();
            $job->setContainer($this->container);

            if($job) {
                //Job exists
                if(!$job->pass()) {
                    //Job is not done!
                    //Clear DIC
                    $job->setContainer();
                    $jobqueue->addJobToQueue($job);
                }

                //Could be another job
                return new Response(json_encode(false));

            } else {
                //Job does not exist
                return new Response(json_encode(true));
            }
        } else {

            return array('queue' => $queue);
        }
    }
}