<?php
namespace TSEP\Component\Indexer\Engine;

use TSEP\Bundle\SearchBundle\Entity\Page;

interface IndexingEngineInterface {

    /**
     * Parse the page
     * @param Page $page
     */
    function parse(Page $page);
}
