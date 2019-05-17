<?php

namespace AppBundle\Entity;

use AppBundle\Annotation\UserAware;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ListItems
 *
 * @ORM\Table(name="list_items")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ListItemsRepository")
 * @UniqueEntity(fields="name", message="Sorry, this name of list is already in use.")
 * @UserAware(userFieldName="user_id")
 */
class ListItems
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
     * @ORM\Column(name="name", type="string", length=20, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Assert\Length(min=10)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="listItems")
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="listItems")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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
     * @return ListItems
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
     * Set description
     *
     * @param string $description
     *
     * @return ListItems
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set items
     *
     * @param ArrayCollection $items
     *
     * @return ListItems
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        $itemsNotConcluded = $this->getItems()
            ->filter(function ($item) {
                return !$item->getConcluded();
            });

        if ($itemsNotConcluded->count() > 0) {
            return 'Em andamento';
        }

        return 'Concluida';
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
