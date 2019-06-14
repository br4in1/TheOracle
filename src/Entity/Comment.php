<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Item as Item;

/**
 * Menu
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity
 */
class Comment
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fos_user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="polarity",type="string",length=20,nullable=false)
     */
    private $polarity;

    /**
     * @var string
     * @ORM\Column(name="content",type="string",length=2000,nullable=false)
     */
    private $content;

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

    public function getCanteen(): ?string
    {
        return $this->canteen;
    }

    public function setCanteen(string $canteen): self
    {
        $this->canteen = $canteen;

        return $this;
    }

    public function getPolarity(): ?string
    {
        return $this->polarity;
    }

    public function setPolarity(string $polarity): self
    {
        $this->polarity = $polarity;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}