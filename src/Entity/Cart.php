<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    const COUPON_CODE = 'CODE99';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $qty;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $sub_total_amount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $discount_amount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $total_amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=CartDetails::class, mappedBy="cart_id")
     */
    private $cartDetails;

    public function __construct()
    {
        $this->cartDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubTotalAmount(): ?string
    {
        return $this->sub_total_amount;
    }

    public function setSubTotalAmount(string $sub_total_amount): self
    {
        $this->sub_total_amount = $sub_total_amount;

        return $this;
    }

    public function getDiscountAmount(): ?string
    {
        return $this->discount_amount;
    }

    public function setDiscountAmount(string $discount_amount): self
    {
        $this->discount_amount = $discount_amount;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): self
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|CartDetails[]
     */
    public function getCartDetails(): Collection
    {
        return $this->cartDetails;
    }

    public function addCartDetail(CartDetails $cartDetail): self
    {
        if (!$this->cartDetails->contains($cartDetail)) {
            $this->cartDetails[] = $cartDetail;
            $cartDetail->setCartId($this);
        }

        return $this;
    }

    public function removeCartDetail(CartDetails $cartDetail): self
    {
        if ($this->cartDetails->contains($cartDetail)) {
            $this->cartDetails->removeElement($cartDetail);
            // set the owning side to null (unless already changed)
            if ($cartDetail->getCartId() === $this) {
                $cartDetail->setCartId(null);
            }
        }

        return $this;
    }

    public function getCartItemCount($session)
    {
        return $session->get('cart') ? count($session->get('cart')) :0;
    }

    public function getItemTotal($session, $coupon = null)
    {
        $items  = $session->get('cart');
        if($items)
            return $this->getItemSubTotal($items) - $this->getTotalDiscount($items, $coupon);
        else
            return 0;
    }
    
    public function getItemSubTotal($array)
    {
        $total = 0;
        foreach ($array as $key => $value) {
            $total += $value['price'];
        }
        return $total;
    }

    public function getTotalDiscount($array, $couponCode = null)
    {
        $totalDiscount = 0;

        $childrenBooks = array_filter($array,function ($array){

            if($array['category_id'] == Book::CHILDREN_CATEGORY){
                return true;
            }
            return false;
        });

        $fictionBooks = array_filter($array,function ($array){
            if($array['category_id'] == Book::FICTION_CATEGORY){
                return true;
            }
            return false;
        });

        if($couponCode == Cart::COUPON_CODE) {
            return ($this->getItemSubTotal($childrenBooks) + $this->getItemSubTotal($fictionBooks)) * 15 / 100;
        }

        $childrenBookCount  = $this->getItemCount($childrenBooks);
        $fictionBookCount  = $this->getItemCount($fictionBooks);

        if($childrenBookCount >= 5) {
            $totalDiscount = $this->getItemSubTotal($childrenBooks) * 10 / 100;
        }

        if($childrenBookCount >= 10 && $fictionBookCount >= 10) {
            $totalDiscount += ($this->getItemSubTotal($childrenBooks) + $this->getItemSubTotal($fictionBooks)) * 5 / 100;
        }

        return round($totalDiscount,2);
    }

    public function getItemCount($array)
    {
        $sum = 0;
        foreach ($array as $key => $value){
            $sum += $value['qty'];
        }
        return $sum;
    }
}
