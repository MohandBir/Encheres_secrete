<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EncheresController extends AbstractController
{
    #[Route('/{id<[0-9]+>?null}', name: 'app_encheres')]
    public function index(?Category $category, ItemRepository $itemRepo, CategoryRepository $categoryRepo, EntityManagerInterface $em): Response
    {
        $id = $category ? (int) $category->getId() : null;
        $items = $itemRepo->findByCategory($id);

        return $this->render('encheres/index.html.twig', [
            'items' => $items,
            'categories' => $categoryRepo->findAll(),
        ]);
    }
 
    #[Route('/show/{id}', name: 'app_encheres_show')]
    public function show($id, Request $request,Item $item, OfferRepository $offerRepo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(OfferType::class);
        $form->handleRequest($request);

        $existingOffer = $offerRepo->findOneBy([
            'user' => $this->getUser(),
            'item' => $item,
        ]);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($item->getStatus() === 'closed') {
                $this->addFlash('danger', 'Cet enchères est fermée!');

                return $this->redirectToRoute('app_encheres_show', ['id' => $id]);
            }
            if ($existingOffer) {
                $this->addFlash('danger', 'vous avez déja un offre pour cet ojbet !');
                return $this->redirectToRoute('app_encheres_show', ['id' => $id]);
            } 
            $offer = new Offer;
            $offer->setAmount($form->get('amount')->getData())
                ->setUser($this->getUser())
                ->setItem($item)
                ;
            if ($offer->getAmount() <= $item->getStartingPrice()) {
                $form->get('amount')->addError(
                new FormError('Le montant doit être supérieur au prix de départ'));
            }
            if ($form->isValid()) {
                $em->persist($offer);
                $em->flush();
                $this->addFlash('success', 'L\'enchère est placée avec succès');

                return $this->redirectToRoute('app_encheres');
            }
        }
 
        return $this->render('encheres/show.html.twig', [
            'item' => $item,
            'formView' => $form->createView(),
        ]);
    }
}
