<?php
namespace TSEP\Component\Indexer\Engine;

use TSEP\Bundle\SearchBundle\Entity\Page;

class IndexingEngine implements IndexingEngineInterface {

	/**
	 * @var array list of stopwords
	 */
	protected $stopwords;

	public function setStopwords(array $stopwords) {

	    $this->stopwords = $stopwords;
	}

	public function getStopwords() {

	    return $this->stopwords;
	}

	private function _cleanText($text) {

			//Clean out all the HTML
			$text = $this->htmlToText($text);

			//Clean out all the stopwords
			foreach ($this->stopwords as $stopword)
				$text = str_replace(' '.$stopword.' ', ' ', $text);

			//Clean out the escape characters
			$text = preg_replace('/(&)((?:[a-z][a-z]+))(;)/is', ' ', $text);
			$text = preg_replace('/(&)(#)(\\d+)(;)/is', ' ', $text);

			//Clean out all the spaces
			$text = preg_replace('!\s+!', ' ', $text);

			$text = trim($text);

			return $text;
	}



	/**
	 * parse
	 * Parses a Page and returns the parsed page
	 * @var Page $page The page to parse
	 */
	function parse (Page $page) {

		return $this->_parse($page);

	}

	/**
	 * _parse
	 * Private function that parses the page and returns
	 * @param Page $page
	 */
	private function _parse($page) {

		//If the page is not html
		if($page->type != 'text/html') return false;

		//Only keep the body
		$page->content = preg_replace("/.*<body[^>]*>|<\/body>.*/si", "", $page->content);

		//Get the Important text
		$page->content = $this->_cleanText($page->content);

		return $page;

	}

	private function htmlToText ($html) {

		return strip_tags($html);
	}

}