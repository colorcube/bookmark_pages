<?php
namespace Colorcube\BookmarkPages\Model;

/*
 * This file is part of the Bookmark Pages TYPO3 extension.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Bookmark {

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var integer
     */
    protected $pid;
    /**
     * @var string
     */
    protected $parameter;


    /**
     * Bookmark constructor.
     * Initialize the bookmark with data
     *
     * @param string|array $url Full url or bookmark data array (same as array from toArray())
     * @param null $title
     * @param null $pid page id
     * @param null $parameter
     */
    public function __construct($url, $title=null, $pid=null, $parameter=null)
    {
        if (is_array($url)) {
            $this->id = $url['id'];
            $this->title = $url['title'];
            $this->url = $url['url'];
            $this->pid = $url['pid'];
            $this->parameter = $url['parameter'];

        } else {
            $this->id = md5($pid.':'.$parameter);
            $this->title = $title;
            $this->url = $url;
            $this->pid = $pid;
            $this->parameter = $parameter;
        }
    }

    /**
     * Create bookmark from the current TSFE page
     *
     * @param string url to bookmark, if null TYPO3_REQUEST_URL will be used - which is wrong when we're in ajax context, then we use HTTP_REFERER
     * @return Bookmark
     */
    public static function createFromCurrent($url = null)
    {
        if ($url === null) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
                //request is ajax
                $url = GeneralUtility::getIndpEnv('HTTP_REFERER');
            } else {
                $url = GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
            }
        }


        $pid = self::getFrontend()->id;
        $title = self::getCurrentPageTitle();

        /*

        The idea was to store get parameters to make bookmark handling more flexible.
        Unfortunately that didn't worked out.

        When we use ajax to trigger bookmarking the current page, we can pass the current url as parameter.
        But the url doesn't have the parameters in it when you use speaking urls (realurl, simulatestatic, ...).
        The problem is that there's no common api to decode urls and get the parameters.

        One solution would be to make the parameters available to the ajax javascript during page rendering.

        We skip all this and use a bit from the url for hashing and add the page id.

         */

        $urlParts = parse_url($url);
        $parameter = $urlParts['path'].'?'.$urlParts['query'].'#'.$urlParts['fragment'];

        return new self($url, $title, $pid, $parameter);


        /*
         * So what is the idea of storing the pid and the get vars?
         *
         * This might makes sense if urls changed for the same page (realurl).
         * With this information the new working url can be restored.
         *
         * Not sure which way is better ...
         */
        //        $parameter = (array)GeneralUtility::_GET();
        //        unset($parameter['id']);
        //        // @todo remove cHash?
        //        ksort($parameter);
        //        $parameter = $parameter ? GeneralUtility::implodeArrayForUrl(false, $parameter) : '';
        //
        //        return new self($url, $title, $pid, $parameter);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param string $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }


    /**
     * Returns the bookmark data as array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'pid' => $this->pid,
            'parameter' => $this->parameter,
        ];
    }


    /**
     * Get the current page title
     * @return string
     */
    protected static function getCurrentPageTitle()
    {
        return self::getFrontend()->altPageTitle? self::getFrontend()->altPageTitle : self::getFrontend()->page['title'];
    }


    /**
     * Get global frontend user
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected static function getFrontend()
    {
        return $GLOBALS['TSFE'];
    }

}