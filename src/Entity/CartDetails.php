<?php

namespace App\Entity;

use App\Repository\CartDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartDetailsRepository::class)
 */
class CartDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="cartDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book_id;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $qty;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $unit_price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?Book
    {
        return $this->book_id;
    }

    public function setBookId(?Book $book_id): self
    {
        $this->book_id = $book_id;

        return $this;
    }

    public function getCartId(): ?Cart
    {
        return $this->cart_id;
    }

    public function setCartId(?Cart $cart_id): self
    {
        $this->cart_id = $cart_id;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unit_price;
    }

    public function setUnitPrice(string $unit_price): self
    {
        $this->unit_price = $unit_price;

        return $this;
    }
}
