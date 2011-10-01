<?php
namespace TSEP\Component\Indexer\Engine;

use TSEP\Bundle\SearchBundle\Entity\Page;

interface CrawlingEngineInterface {

    /**
     * Advance the crawler and return the page contents
     * @return Page
     */
    function crawl();
}
