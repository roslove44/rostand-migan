<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', []);
    }

    #[Route('/blog/post', name: 'app_blog_post')]
    public function post(): Response
    {
        return $this->render('blog/post.html.twig', []);
    }
}
