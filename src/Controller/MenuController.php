<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Order;
use App\Form\OrderDTO;
use App\Form\Type\OrderType;
use App\Repository\MenuRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    #[Route(['/', '/menu'], name: 'menu')]
    public function menu(
        MenuRepository $menuRepository,
        OrderRepository $orderRepository,
    ): Response {
         $menuItems = $menuRepository->findByCriteria([], 10);

        return $this->render(
            'menu.html.twig',
            [
                'menuItems' => array_map(fn(Menu $menu) => $menu->toArray(), $menuItems),
                'form' => $this->createForm(OrderType::class, null, ['action' => $this->generateUrl('place_order'),]),
                'queue' => $orderRepository->getQueueSummary(),
            ]
        );
    }

    #[Route('/order', name: 'place_order', methods: ['POST'])]
    public function placeOrder(
        Request $request,
        OrderRepository $orderRepository
    ): Response {
        $orderDTO = new OrderDTO();
        $form = $this->createForm(OrderType::class, $orderDTO);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $order = Order::createFromDTO($orderDTO);
                $orderRepository->save($order);
                $this->addFlash('success', 'Order placed successfully');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Order placement failed: ' . $e->getMessage());
            }
        } else {
            $this->addFlash('danger', 'Order placement failed');
        }

        return $this->redirectToRoute('menu');
    }
}
