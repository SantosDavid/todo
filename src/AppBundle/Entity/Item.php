<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="concluded", type="boolean")
     */
    private $concluded = '0';


    /**
     * @ORM\ManyToOne(targetEntity="ListItems", inversedBy="items")
     * @ORM\JoinColumn(name="list_items_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $listItems;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set concluded
     *
     * @param boolean $concluded
     *
     * @return Item
     */
    public function setConcluded($concluded)
    {
        $this->concluded = $concluded;

        return $this;
    }

    /**
     * Get concluded
     *
     * @return bool
     */
    public function getConcluded()
    {
        return $this->concluded;
    }

    /**
     * Set concluded
     *
     * @param ListItems $listItems
     *
     * @return Item
     */
    public function setListItems($listItems)
    {
        $this->listItems = $listItems;

        return $this;
    }

    /**
     * Get listItems
     *
     * @return ListItems
     */
    public function getlListItems()
    {
        return $this->listItems;
    }
}

