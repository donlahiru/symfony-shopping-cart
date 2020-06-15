<?php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\Cart;
use App\Form\BookType;
use App\Form\CartType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Session\Session;

class BookController extends AbstractController
{
    /**
     * @Route("/",methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $books = $this->getDoctrine()->getRepository(Book::class);
            $cart   = new Cart();

            if(is_null($request->query->get('category')))
                $books = $books->findAll();
            else
                $books = $books->findBy(['category_id'=> $request->query->get('category')]);

            return $this->render('books/index.html.twig',[
                'books' => $books,
                'cart_item_count' => $cart->getCartItemCount(new Session()),
                'total' => number_format($cart->getItemTotal(new Session()),2)
            ]);
        }catch (\Exception $e){
            return $this->render('error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/book/new", methods={"GET","POST"})
     * @param Request $request
     *
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        try {
            $book = new Book();
            $book->setCreatedAt(new \DateTime("now"));
            $form = $this->createForm(BookType::class, $book);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $bookImageFile = $form['image']->getData();
                if ($bookImageFile) {
                    $bookImageFileName = $fileUploader->upload($bookImageFile);
                    $book->setImage($bookImageFileName);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($book);
                $em->flush();
                return $this->redirect('/');
            }


            return $this->render('books/create.html.twig',[
                'book_form' => $form->createView()
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/book/{id}", methods={"GET","POST"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $form = $this->createForm(CartType::class);
        $session    = $request->getSession();
        $addedQty   = 0;

        $items = $session->get('cart');
        
        if(isset($items[$id])){
            $addedQty = $items[$id]['qty'];
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            if($addedQty > 0) {
                $items[$id]['qty'] += $form['qty']->getData();
                $items[$id]['price'] = $items[$id]['qty'] * $items[$id]['unit_price'];
            } else {
                $items[$id] = [
                        'name'=> $book->getName(),
                        'author'=> $book->getAuthor(),
                        'unit_price' => $book->getUnitPrice(),
                        'category_id' => $book->getCategoryId(),
                        'category' => $book->getCategoryName(),
                        'image' => $book->getImage(),
                        'price' => round(($book->getUnitPrice() * $form['qty']->getData()),2),
                        'qty' => $form['qty']->getData()
                    ];
            }
            $session->set('cart',$items );
            return $this->redirect('/');
        }

        return $this->render('books/show.html.twig',[
            'book' => $book,
            'cart_form' => $form->createView(),
            'added_qty' => $addedQty
        ]);
    }
}