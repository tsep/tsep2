<?php
namespace TSEP\Bundle\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Search {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	 protected $id;

	/**
	 * @ORM\Column(type="string", length="255")
	 */
	protected $query;


    /**
     *@ORM\ManyToOne(targetEntity="Profile", inversedBy="Search")
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

    /**
     * Set query
     *
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Get query
     *
     * @return string $query
     */
    public function getQuery()
    {
        return $this->query;
    }
}