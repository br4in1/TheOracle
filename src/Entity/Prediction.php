<?php
/**
 * Created by PhpStorm.
 * User: br4in
 * Date: 2019-05-23
 * Time: 12:23
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Item
 *
 * @ORM\Table(name="predictions")
 * @ORM\Entity
 */
class Prediction
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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="canteen", type="string",length = 12)
     */
    private $canteen;

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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