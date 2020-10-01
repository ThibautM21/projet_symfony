<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}", name="", requirements={"_locale": "en|fr|"})
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ArticleRepository $articleRepository)
    {
        // // $article = ['test' => 2, 'test3' => 4];
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRepository->getArticlePaginator($offset);
        return $this->render('article/index.html.twig', [
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE)
        ]);

        // $locale = $request->getPreferredLanguage();
        // return $this->render('home/contact.html.twig');
        // return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('home/contact.html.twig');
    }
}
