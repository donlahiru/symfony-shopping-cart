<?php
namespace App\Test\unit;

use App\Entity\Cart;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class CartTest extends TestCase
{
    private $cart;

    protected function setUp()
    {
        $this->cart = new Cart();
    }

    public function testCartCount()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 1000,
                'qty' => 1
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 2000,
                'qty' => 1
            ]
        ];

        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(2, $this->cart->getCartItemCount($session));
    }

    public function testCartSubTotal()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 1000,
                'qty' => 1
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 2
            ]
        ];
        self::assertEquals(5000,$this->cart->getItemSubTotal($array));
    }

    public function testTotalDiscountWhenChildrenBookLessThanFiveAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 2
            ]
        ];
        self::assertEquals(0,$this->cart->getTotalDiscount($array));
    }

    public function testTotalWhenChildrenBookLessThanFiveAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 2
            ]
        ];
        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(8000,$this->cart->getItemTotal($session));
    }

    public function testTotalDiscountWhenChildrenBookLessThanFiveAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 2
            ]
        ];
        self::assertEquals(1200,$this->cart->getTotalDiscount($array,'CODE99'));
    }
    
    public function testTotalWhenChildrenBookLessThanFiveAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 2
            ]
        ];
        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(6800,$this->cart->getItemTotal($session, 'CODE99'));
    }

    public function testTotalDiscountWhenChildrenBookGreaterThanFiveAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 5
            ]
        ];
        self::assertEquals(1000,$this->cart->getTotalDiscount($array));
    }

    public function testTotalWhenChildrenBookGreaterThanFiveAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 5
            ]
        ];
        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(13000,$this->cart->getItemTotal($session));
    }

    public function testTotalDiscountWhenChildrenBookGreaterThanFiveAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 5
            ]
        ];
        self::assertEquals(2100,$this->cart->getTotalDiscount($array, 'CODE99'));
    }

    public function testTotalWhenChildrenBookGreaterThanFiveAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 4000,
                'qty' => 4
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 5
            ]
        ];
        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(11900,$this->cart->getItemTotal($session, 'CODE99'));
    }

    public function testTotalDiscountWhenEachCategoryGreaterThanTenAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 10
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 20000,
                'qty' => 10
            ]
        ];
        self::assertEquals(3500,$this->cart->getTotalDiscount($array));
    }

    public function testTotalWhenEachCategoryGreaterThanTenAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 10
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 20000,
                'qty' => 10
            ]
        ];
        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(26500,$this->cart->getItemTotal($session));
    }

    public function testTotalDiscountWhenEachCategoryGreaterThanTenAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 10
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 20000,
                'qty' => 10
            ]
        ];
        self::assertEquals(4500,$this->cart->getTotalDiscount($array, 'CODE99'));
    }

    public function testTotalWhenEachCategoryGreaterThanTenAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'sherlock holmes',
                'author' => 'Aurthur conan doyle',
                'unit_price' => 1000,
                'category_id' => Book::FICTION_CATEGORY,
                'category' => 'Fiction',
                'image' => 'abc.jpg',
                'price' => 10000,
                'qty' => 10
            ],
            [
                'id' => 2,
                'name' => 'Harry potter',
                'author' => 'J K Rowling',
                'unit_price' => 2000,
                'category_id' => Book::CHILDREN_CATEGORY,
                'category' => 'Children',
                'image' => 'abc.jpg',
                'price' => 20000,
                'qty' => 10
            ]
        ];
        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(25500,$this->cart->getItemTotal($session, 'CODE99'));
    }

}