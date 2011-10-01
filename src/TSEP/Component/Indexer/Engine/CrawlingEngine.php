<?php
namespace TSEP\Component\Indexer\Engine;

use TSEP\Component\Indexer\Helper\HTML;
use TSEP\Component\Indexer\Helper\URL;
use TSEP\Bundle\SearchBundle\Entity\Page;

class CrawlingEngine implements CrawlingEngineInterface {

    /**
     * The regular expression that urls must match to be crawled
     * @var string
     */
    protected $regularExpression = '';

    /**
     * The user agent to act as
     * @var string
     */
    protected $userAgent = '';

    /**
     * URLS that are queued to be crawled
     * @var array
     */
    protected $queuedURLs = array();

    /**
     * URLs that have already been crawled
     * @var array
     */
    protected $crawledURLs = array();


    public function addQueuedURL($queuedURL) {

        return array_push($this->queuedURLs, $queuedURL);
    }

    public function addCrawledURL ($crawledURL) {

        return array_push($this->crawledURLs, $crawledURL);
    }

    public function setRegularExpression($regularExpression) {

        return $this->regularExpression = $regularExpression;
    }

    public function setUserAgent($userAgent) {

        return $this->userAgent = $userAgent;
    }


    public function getUserAgent() {

        return $this->userAgent;
    }

    public function getRegularExpression() {

        return $this->regularExpression;
    }

    public function getCrawledURLs() {

        return $this->crawledURLs;
    }

    public function getQueuedURLs () {

        return $this->queuedURLs;
    }

    public function isURLCrawled($crawledURL) {

        return in_array($crawledURL, $this->crawledURLs);
    }

    public function isURLQueued($queuedURL) {

        return in_array($queuedURL, $this->queuedURLs);
    }


    /**
     * Advances the crawler to the next URL and returns the page contents
     * @return Page
     */
    public function crawl() {

        $currentURL = array_pop($this->queuedURLs);

        $parsedURL = parse_url($currentURL);

        $robotsURL = "http://{$parsedURL['host']}/robots.txt";

        if($this->isURLCrawled($robotsURL)) {

            //Robots is parsed; retreive page

            $crawledPage = $this->crawlPage($currentURL);

            return $crawledPage;
        }
        else {

            //Parse the robots

            //Re-Queue the current URL

            $this->addQueuedURL($currentURL);

            $this->queuedURLs = array_merge($this->queuedURLs, HTML::parseRobots($robotsURL, $this->userAgent));
        }

    }

    protected function crawlPage($pageURL) {

        if ($pageContents = $this->getPageContents($pageURL)) {

            $this->queuedURLs = array_merge($this->queuedURLs, HTML::parsePage($pageContents, $pageURL));

            $this->cleanURLs();

            $crawledPage = new Page();

            $crawledPage->setText($pageContents);
            $crawledPage->setUrl($pageURL);

            return $crawledPage;
        }

    }

    protected function getPageContents ($pageURL) {

        $streamContext = stream_context_create(array(
            'http' => array(
                'timeout' => 5      // Timeout in seconds
            )
        ));

        return @file_get_contents($currentURL, 0, $streamContext);
    }


    /**
     * cleanURLs
     * Removes all URLs that are duplicates, have already been crawled, or do not match the RegEx
     */
    protected function cleanURLs () {

        //Remove duplcates
        $this->queuedURLs = array_unique($this->queuedURLs);
        $this->crawledURLs = array_unique($this->crawledURLs);

        //Remove done urls
        foreach ($this->queuedURLs as $key => $value)
            if(in_array($value, $this->crawledURLs))
                unset($this->queuedURLs[$key]);

        foreach ($this->urls as $key => $value) {
            //Check that the URL is on the same domain
            if (!preg_match($this->regularExpression, $value))
                unset($this->queuedURLs[$key]);
        }

        //Reindex the arrays
        $this->crawledURLs = array_values($this->crawledURLs);
        $this->queuedURLs = array_values($this->queuedURLs);
    }

}
