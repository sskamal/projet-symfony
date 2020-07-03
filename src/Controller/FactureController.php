<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Article;
use App\Entity\FactureArticle;
use App\Form\ArticleFactureType;
use App\Form\ArticleType;
use App\Form\FactureType;
use App\Repository\ArticleRepository;
use App\Repository\FactureArticleRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class FactureController extends AbstractController
{
    /**
     * @Route("/factures", name="factures")
     */
    public function factures(FactureRepository $repof)
    {
       $facture=new Facture();
        $facture = $repof->findAll();
        return $this->render('facture/index.html.twig', [
            'factures' =>$facture
        ]);
    }



    /**
     * @Route("/factures/{id}" , name="edit_facture")
     */
    public function show_article(Facture $facture, Request $request,FactureArticle $detailfacture = null, FactureRepository $repofacture,FactureArticleRepository $repodetailsfacture, $id)
    {
        if($detailfacture)
       {
            $detailfacture = new FactureArticle();
        }

        $facture = $repofacture->find($id);
        $formfacture= $this->createForm( FactureType::class, $facture);
        $formfacture->handleRequest($request);

      

        $formdetailfacture= $this->createForm( ArticleFactureType::class, $detailfacture );
        $formdetailfacture->handleRequest($request);


        if($formfacture->isSubmitted() && $formfacture->isValid()) {
            // $article->setCreatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute("edit_facture", [
                'id' => $facture->getId()
            ]);
        }

        if($formdetailfacture->isSubmitted() && $formdetailfacture->isValid()) {

          $detailfacture->setFactures($facture);
         // $detailfacture->setArticles($detailfacture->getArticles()->getId());

           $detailfacture->setDescription($detailfacture->getArticles()->getDescription());
           $detailfacture->setTotal(($detailfacture->getPrix())*($detailfacture->getQuantite()));
//$detailfacture->setArticles();
            $entityManager = $this->getDoctrine()->getManager();

            $pq= (double)$detailfacture->getPrix()* (int)$detailfacture->getQuantite();

            $entityManager->persist($detailfacture);
            $entityManager->flush();

            $totalparfacture = (double)$repodetailsfacture->totalParFacture($facture);

            $facture->setTotal( $totalparfacture);
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute("edit_facture", [
                'id' => $facture->getId()
            ]);
        }


        return $this->render('facture/modifier.html.twig', [
            'formfacture' => $formfacture->createView(),
            'formdetailfacture' => $formdetailfacture->createView(),
            'detailfacture' => $detailfacture,
            'facture'=> $facture,


        ]);
    }

    /**
     * @Route("/facture/nouvelle", name="ajouter_facture")
     */
    public function nouvelle_facture(Request $request, ArticleRepository $repo)
    {

        $facture=new Facture();
        $formfacture=$this->createForm(FactureType::class,$facture);
        $formfacture->handleRequest($request);

        $article_facture=new FactureArticle();
        $form=$this->createForm(ArticleFactureType::class, $article_facture);
        $form->handleRequest($request);


        if($formfacture->isSubmitted() && $formfacture->isValid())
        {
        

                // $article->setCreatedAt(new \DateTime());
                $facture->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($facture);
                $entityManager->flush();

            return $this->redirectToRoute("edit_facture",[
                'id'=>$facture->getId()
            ]);


        }


      //  if($form->isSubmitted() && $form->isValid())
       // {
            // $article->setCreatedAt(new \DateTime());
          //  $entityManager = $this->getDoctrine()->getManager();
           // $entityManager->persist($article_facture);
           // $entityManager->flush();
      //  }

        return $this->render('facture/nouvelle.html.twig', [
            'form' => $form->createView(),
            'formfacture' => $formfacture->createView()
        ]);
    }
                    /** 
                  * @Route("/facture/delete/{id}")
                  * @Method({"DELETE"})
                 */
                public function delete(Request $request, $id ) {
                    $detailfacture =$this->getDoctrine()->getRepository(FactureArticle::class)->find($id);
                   
                    $entitymanager = $this->getDoctrine()->getManager();
                    $entitymanager->remove($detailfacture);
                    $entitymanager->flush();

                    $respone= new Response();
                    $respone->send();
                 }
}
