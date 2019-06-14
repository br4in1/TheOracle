<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Item as Item;

/**
 * Menu
 *
 * @ORM\Table(name="menu", indexes={@ORM\Index(name="item", columns={"item"})})
 * @ORM\Entity
 */
class Menu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="canteen", type="string", nullable=false, length = 12)
     */
    private $canteen;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item", referencedColumnName="id")
     * })
     */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getCanteen(): ?string
    {
        return $this->canteen;
    }

    public function setCanteen(string $canteen): self
    {
        $this->canteen = $canteen;

        return $this;
    }


}
