<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SeoController extends AbstractController
{
    #[Route('/robots.txt', name: 'app_seo_robots')]
    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /api/\n";
        $content .= "Disallow: /dashboard/\n";
        $content .= "Disallow: /pages/\n";
        $content .= "Disallow: /register/\n";
        $content .= "Disallow: /login/\n";
        $content .= "Sitemap: https://www.rostand-migan.com/sitemap.xml\n";

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }
}
