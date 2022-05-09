<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $repoArticle;

    private $repoCategory;

    public function __construct(ArticleRepository $repoArticle, CategoryRepository $repoCategory)
    {
        $this->repoArticle = $repoArticle;
        $this->repoCategory = $repoCategory;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $categories = $this->repoCategory->findAll();
        $articles = $this->repoArticle->findAll();

        $articlesPag = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/index.html.twig', [
            "articles" => $articlesPag,
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Article $article): Response
    {
        if (!$article)
        {
            $this->redirectToRoute("home");
        }

        return $this->render('show/index.html.twig', [
            "article" => $article
        ]);
    }

    /**
     * @Route("/showArticles/{id}", name="show_articles")
     */
    public function showArticles(?Category $category, Request $request, PaginatorInterface $paginator): Response
    {
        if ($category)
        {
            $articles = $category->getArticles()->getValues();
        }
        else
        {
            return $this->redirectToRoute("home");
        }

        $articlesPag = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/index.html.twig', [
            "articles" => $articlesPag,
            "categories" => $this->repoCategory->findAll()
        ]);
    }


}
