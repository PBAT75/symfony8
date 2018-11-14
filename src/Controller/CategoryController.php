<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="showAllArticles")
     */
    public function showAllArticles(Category $category)
    {
        return $this->render('category/show_all_articles.html.twig', [
            'category' => $category,
        ]);
    }
}
