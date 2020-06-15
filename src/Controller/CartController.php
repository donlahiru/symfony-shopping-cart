<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Form\CheckoutType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index()
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/checkout", name="checkout", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        try {
            $cart = new Cart();
            $form = $this->createForm(CheckoutType::class);
            $session = $request->getSession();
            $items = $session->get('cart');
            $coupon = null;

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $coupon = $form['coupon']->getData();
            }

            return $this->render('cart/checkout.html.twig', [
                'books' => $items,
                'discount' => number_format($cart->getTotalDiscount($items, $coupon), 2),
                'sub_total' => number_format($cart->getItemSubTotal($items), 2),
                'total' => number_format($cart->getItemTotal($session, $coupon), 2),
                'checkout_form' => $form->createView()
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/remove-cart", name="remove-cart", methods={"GET"})
     */
    public function destroy()
    {
        try {
            $session = new Session();
            $session->remove('cart');
            return $this->redirect('/');
        }catch (\Exception $e){
            return $this->render('error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
    }


}
