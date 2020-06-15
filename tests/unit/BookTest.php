<?php
namespace App\Test\unit;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    private $book;

    protected function setUp()
    {
        $this->book = new Book();
    }

    public function testGetSetName()
    {
        self::assertEquals(null,$this->book->getName());
        $this->book->setName('sherlock holmes');
        self::assertEquals('sherlock holmes',$this->book->getName());
    }

    public function testGetSetAuthor()
    {
        self::assertEquals(null,$this->book->getAuthor());
        $this->book->setAuthor('Aurthur conan doyle');
        self::assertEquals('Aurthur conan doyle',$this->book->getAuthor());
    }

    public function testGetSetCategoryId()
    {
        self::assertEquals(null,$this->book->getCategoryId());
        $this->book->setCategoryId(Book::FICTION_CATEGORY);
        self::assertEquals(Book::FICTION_CATEGORY,$this->book->getCategoryId());
    }

    public function testGetSetDescription()
    {
        self::assertEquals(null,$this->book->getDescription());
        $this->book->setDescription('Sherlock Holmes is a character');
        self::assertEquals('Sherlock Holmes is a character',$this->book->getDescription());
    }

    public function testGetSetImage()
    {
        self::assertEquals(null,$this->book->getImage());
        $this->book->setImage('abc.jpg');
        self::assertEquals('abc.jpg',$this->book->getImage());
    }

    public function testGetSetUnitPrice()
    {
        self::assertEquals(null,$this->book->getUnitPrice());
        $this->book->setUnitPrice('200');
        self::assertEquals('200',$this->book->getUnitPrice());
    }

    public function testGetSetCreatedAt()
    {
        self::assertEquals(null,$this->book->getCreatedAt());
        $this->book->setCreatedAt(new \DateTime("2020-06-15 15:30:00"));
        self::assertEquals(new \DateTime("2020-06-15 15:30:00"),$this->book->getCreatedAt());
    }

    public function testGetSetUpdatedAt()
    {
        self::assertEquals(null,$this->book->getUpdatedAt());
        $this->book->setUpdatedAt(new \DateTime("2020-06-15 15:31:00"));
        self::assertEquals(new \DateTime("2020-06-15 15:31:00"),$this->book->getUpdatedAt());
    }

    public function testCategoryName()
    {
        $this->book->setCategoryId(Book::FICTION_CATEGORY);
        self::assertEquals('Fiction',$this->book->getCategoryName());
        $this->book->setCategoryId(Book::CHILDREN_CATEGORY);
        self::assertEquals('Children',$this->book->getCategoryName());
    }
}