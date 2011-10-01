<?php

namespace TSEP\Bundle\AdminBundle\Job;

use Symfony\Component\DependencyInjection\Container;
use Xaav\QueueBundle\JobQueue\Job\AbstractJob;
use TSEP\Component\Indexer\Engine\CrawlingEngine;
use TSEP\Component\Indexer\Engine\IndexingEngine;
use Xaav\QueueBundle\JobQueue\Job\JobInterface;
use TSEP\Bundle\SearchBundle\Entity\Profile;

class ProcessIndexingRequestJob extends AbstractJob implements JobInterface
{
    protected $profile;

    /**
     * @var CrawlingEngine
     */
    protected $crawlingEngine;

    /**
     * @var IndexingEngine
     */
    protected $indexingEngine;

    /**
     * @var Container
     */
    protected $container;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function setContainer(Container $container = null)
    {
        $this->container = $container;
    }

    protected function init()
    {
        $this->indexingEngine = new IndexingEngine();
        $this->crawlingEngine = new CrawlingEngine();


        //TODO: Add this to the profile
        $this->crawlingEngine->setUserAgent('The Search Engine Project version 2.0');

        $this->crawlingEngine->setRegularExpression($this->profile->getRegex());
        $this->crawlingEngine->addQueuedURL($this->profile->getUrl());

        //TODO: Add custom stopwords
        $this->indexingEngine->setStopwords(array());
    }

    public function process($count)
    {
        //TODO: Save state between passes

        $pages = array();

        if ($page = $this->crawlingEngine->crawl()) {
            $page = $this->indexingEngine->parse($page);

            $em = $this->get('doctrine')->getEntityManager();
            $em->persist($page);
            $em->flush();

            return true;
        }

        return false;
    }
}
