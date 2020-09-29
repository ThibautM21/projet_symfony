<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/article", name="article_")
 */
class ArticleController extends AbstractController
{
    private $ar;
    private $em;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $manager)
    {
        $this->ar = $articleRepository;
        $this->em = $manager;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        // $articles = $this->ar->findAll();
        // dd($articles);
        // $articles = $this->ar->findByTitle('nouvel Article 2');
        $articles = $this->ar->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $newArticle = new Article();
        // $newArticle
        //     ->setTitle('nouvel Article 7')
        //     ->setContent('Occaecat duis pariatur labore nisi laborum ad aliquip. Duis do esse qui qui. Tempor labore consectetur cillum consequat in mollit magna quis officia aliqua veniam. Ipsum incididunt commodo Lorem ipsum nisi. Nulla quis laboris ea aliquip commodo tempor ipsum consectetur aliquip qui ullamco Lorem.')
        //     ->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

        // Dans la barre de Symfony
        // dump($newArticle);
        // Directement dans la page
        // dd($newArticle);

        // Récupérer le manager présent dans la classe AbstractController
        // $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ArticleType::class, $newArticle, [
            'action' => $this->generateUrl('article_new')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // // Persist dans Doctrine
            $this->em->persist($newArticle);

            // // Flush dans la DB
            $this->em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $newArticle,
            'form' => $form->createView()
        ]);

        // return $this->render('article/index.html.twig', [
        //     'articles' => $this->ar->findAll()
        // ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"page"="\d+"})
     */
    public function edit(Request $request, Article $article)
    {
        // $newArticle = $this->ar->find($id);

        $form = $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('article_edit', ['id' => $article->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // // Flush dans la DB
            $this->em->flush();
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);

        // return $this->render('article/index.html.twig', [
        //     'articles' => $this->ar->findAll()
        // ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"page"="\d+"})
     */
    public function delete(Article $article)
    {
        $this->em->remove($article);
        $this->em->flush();
        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/{id}", name="alone", requirements={"id"="\d+"})
     */
    public function article(Article $article)
    {
        if ($article)
            return $this->render('article/show.html.twig', [
                'article' => $article
            ]);
        else
            return $this->render('article/index.html.twig', [
                'articles' => $this->ar->findAll()
            ]);
    }
}
