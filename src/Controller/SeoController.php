<?php

namespace App\Controller;

use App\Service\SitemapDateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    #[Route('/sitemap.xml', name: 'app_seo_sitemap')]
    public function sitemap(SitemapDateService $sitemapDateService): Response
    {
        $urls = [];
        $firstDayOfCurrentWeek = $sitemapDateService->getFirstDayOfCurrentWeek();

        $urls[] = [
            'loc' => $this->generateUrl(
                'app_main',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'lastmod' => $firstDayOfCurrentWeek,
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ];
        $urls[] = [
            'loc' => $this->generateUrl(
                'app_main_contact',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'lastmod' => $firstDayOfCurrentWeek,
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];
        $urls[] = [
            'loc' => $this->generateUrl(
                'app_resume',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'lastmod' => $firstDayOfCurrentWeek,
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];

        $response = new Response(
            $this->renderView('seo/sitemap.html.twig', ['urls' => $urls]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
