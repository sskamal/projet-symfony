<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
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
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\HttpFoundation\Request;


class PdfController extends AbstractController
{


    /**
     * @Route("/pdf/{id}", name="showpdf")
     */
    public function showfacture( FactureRepository $repof ,$id)
    {
        $factures=new Facture();
       // $factures = $repof->findAll();
        $factures = $repof->find($id);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf/facture.html.twig', [
            'title' => "Welcome to our PDF Test",
            'factures' => $factures
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

    }




    /**
     * @Route("/pdf", name="pdf")
     */
    public function index( FactureRepository $repof)
    {
        $factures=new Facture();
        $factures = $repof->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('impression/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'factures' => $factures
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

    }
}
