<?php
namespace TSEP\Bundle\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Profile
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
    protected $name;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $url;

	/**
     * @ORM\Column(type="string", length="255")
     */
    protected $regex;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="Profile")
     */
    protected $pages;

    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
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
     * Set regex
     *
     * @param string $regex
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Get regex
     *
     * @return string $regex
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Add pages
     *
     * @param TSEP\Bundle\SearchBundle\Entity\Page $pages
     */
    public function addPages(\TSEP\Bundle\SearchBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    }

    /**
     * Get pages
     *
     * @return Doctrine\Common\Collections\Collection $pages
     */
    public function getPages()
    {
        return $this->pages;
    }
}