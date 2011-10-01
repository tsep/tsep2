<?php

namespace TSEP\Component\Indexer\Engine\Helper;

class HTML {

    public function parseRobots ($robotsURL,  $userAgent) {
        # parse url to retrieve host and path


        $agents = array(preg_quote('*'));
        if($userAgent) $agents[] = preg_quote($userAgent);
        $agents = implode('|', $agents);

        # location of robots.txt file
        $robotstxt = @file($robotsURL);
        if(!$robotstxt) return true;

        $rules = array();
        $ruleapplies = false;
        foreach($robotstxt as $line) {
          # skip blank lines
          if(!$line = trim($line)) continue;

          # following rules only apply if User-agent matches $useragent or '*'
          if(preg_match('/User-agent: (.*)/i', $line, $match)) {
            $ruleapplies = preg_match("/($agents)/i", $match[1]);
          }
          if($ruleapplies && preg_match('/Disallow:(.*)/i', $line, $regs)) {
            # an empty rule implies full access - no further tests required
            if(!$regs[1]) return true;
            # add rules that apply to array for testing
            $rules[] = preg_quote(trim($regs[1]), '/');
          }
        }

        $urlsFound = array();

        foreach($rules as $rule) {
          # Push the URL into the 'done' array
                array_push($urlsFound, Url::urlToAbsolute($robotsURL, $rule));
        }

        return $urlsFound;
    }


    /**
     * parse
     * Parses an page and adds all the URLs it can find to $this->urls
     * @param string $contents The contents to parse
     */
    public static function parsePage($pageContents, $pageURL) {

            switch (self::getType($pageContents)) {
                case 'text/html':
                    return $this->parseHTML($pageContents, $pageURL);
                    break;
                case 'text/javascript':
                    return $this->parseJS($pageContents, $pageURL);
                    break;
                case 'text/css':
                    return $this->parseCSS($pageContents, $pageURL);
                    break;
                default: //Attempt to parse all three
                //	$this->parseHTML($contents);
                //	$this->parseCSS($contents);
                //	$this->parseJS($contents);
                    break;
            }

    }

    protected static function parseHTML($pageContents, $pageURL) {

        $dom = new DOMDocument();

        @$dom->recover = true;
        @$dom->loadHTML($pageContents);

        $simple = simplexml_import_dom($dom);

        unset($dom); //DomDocument is heavy and bloated


        $links = $simple->xpath('/html/body//a');

        $urlsFound = array();

        foreach ($links as $link){

            array_push($urlsFound, Url::urlToAbsolute($pageURL, $link['href']));

        }

        return $urlsFound;
    }


    //TODO: Implement Javascript parsing
    /**
     * parseJS
     * Parses links from JavaScript
     * @param string $contents The JavaScript to parse
     */
    protected static function parseJS ($pageContents, $pageURL) {}

    //TODO: Implement CSS parsing
    /**
     * parseCSS
     * Parses links from CSS
     * @param string $contents The CSS to parse
     */
    protected static function  parseCSS ($pageContents, $pageURL) {}

    protected static function getType($pageContents) {}
}
