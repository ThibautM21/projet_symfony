<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        // // $article = ['test' => 2, 'test3' => 4];
        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'Home', //$article,
        // ]);
        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('home/contact.html.twig');
    }
}
