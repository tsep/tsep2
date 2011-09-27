<?php
namespace TSEP\Bundle\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TSEP\Bundle\SearchBundle\Entity\PageRepository")
 */
class Page
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
    protected $id;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $url;

    /**
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     *@ORM\ManyToOne(targetEntity="Profile", inversedBy="Page")
     */
    protected $profile;



    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set profile
     *
     * @param TSEP\Bundle\SearchBundle\Entity\Profile $profile
     */
    public function setProfile(\TSEP\Bundle\SearchBundle\Entity\Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @return TSEP\Bundle\SearchBundle\Entity\Profile $profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}