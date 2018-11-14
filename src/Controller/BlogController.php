<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
//    /**
//     * @Route("/{slug<([a-z0-9-]*)>}", name="show")
//     * @param string $slug
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function show(string $slug='Article sans Titre')
//    {
//        $slug = ucwords(
//            str_replace("-", " ", $slug)
//        );
//
//        return $this->render('blog/show.html.twig', [
//            'slug' => $slug,
//        ]);
//    }

    /**
     * Show all row from article's entity
     *
     * @Route("/", name="blog_index")
     * @return Response A response instance
     */
    public function index() : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show($slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }


    /**
     * Getting one category with this articles
     *
     * @Route("/category/{categoryName}",
     *     defaults={"categoryName" = null},
     *     name = "blog_show_category")
     *
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName) : Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No Category has been sent.');
        }
        $categoryName = preg_replace(
            '/-/',
            ' ', trim(strip_tags($categoryName))
        );
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(
                ['name' => $categoryName]
            );
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(
                ['category' => $category->getId()],
                ['id' => 'desc'],
                3,
                0
            );
        return $this->render(
            'blog/showByCategory.html.twig',
            ['articles' => $articles, 'categoryName' => $categoryName]
        );
    }


}
