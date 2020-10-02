<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRepository->getArticlePaginator($offset);
        return $this->render('article/index.html.twig', [
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE)
        ]);
    }

    /**
     * @Route("/test", name="article_index2", methods={"GET"})
     */
    public function index2(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request)
    {
        $query = $articleRepository->getArticlePaginatorQuery();
        $articles = $paginator->paginate(
            $query, /* query NOT result (pas opti) */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // parameters to template
        return $this->render('article/index2.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $article = new Article($this->getUser());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(ArticleRepository $articleRepository, Request $request, Article $article): Response
    {

        if ($this->getUser()->getEmail() != $article->getAuthor()->getEmail() && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $offset = max(0, $request->query->getInt('offset', 0));
            $paginator = $articleRepository->getArticlePaginator($offset);
            return $this->render('article/index.html.twig', [
                'articles' => $paginator,
                'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE)
            ]);
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(ArticleRepository $articleRepository, Request $request, Article $article): Response
    {
        if ($this->getUser()->getEmail() != $article->getAuthor()->getEmail() && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $offset = max(0, $request->query->getInt('offset', 0));
            $paginator = $articleRepository->getArticlePaginator($offset);
            return $this->render('article/index.html.twig', [
                'articles' => $paginator,
                'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE)
            ]);
        }

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
