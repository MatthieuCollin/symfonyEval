<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function viewProducts(EntityManagerInterface  $entityManager): Response
    {

        $repository = $entityManager->getRepository(Produit::class);
        $produits = $repository->findAll();


        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
            'produits' => $produits
        ]);
    }

    #[Route('/product/{id}', name: 'app_product')]
    public function viewProduct(Request $request,EntityManagerInterface  $entityManager,$id): Response
    {
        $repoProduits = $entityManager->getRepository(Produit::class);
        $produits = $repoProduits->findOneBy(array('id'=>$id));

        $repoCommentaire = $entityManager->getRepository(Commentaire::class);
        $commentaire = $repoCommentaire->findBy(array('produit' => $produits->getId()));

        $newComm = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $newComm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newComm->setProduit($produits);

            $entityManager->persist($newComm);
            $entityManager->flush();

            return $this->redirectToRoute('app_products', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('products/product/index.html.twig', [
            'controller_name' => 'ProductsController',
            'produits' => $produits,
            'commentaires' => $commentaire,
            'form' => $form->createView(),
        ]);
    }
}
