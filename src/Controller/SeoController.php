<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\SitemapService;
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
    public function sitemap(SitemapService $sm, ProjectRepository $projectRepository): Response
    {
        $urls = [];
        $firstDayOfCurrentWeek = $sm->getFirstDayOfCurrentWeek();

        array_push(
            $urls,
            $sm->sitemapUrl('app_main', $firstDayOfCurrentWeek, 1.0),
            $sm->sitemapUrl('app_main_contact', $firstDayOfCurrentWeek, 0.8),
            $sm->sitemapUrl('app_resume', $firstDayOfCurrentWeek, 0.8),
        );

        /**
         * @var Project[]|[] $projects
         */
        $projects = $projectRepository->findBy([], ['updated_at' => 'DESC']);
        if ($projects) {
            $mostRecentUpdatedAt = $projectRepository->findMostRecentUpdatedAt();
            array_push($urls, $sm->sitemapUrl('app_work', $sm->formatSitemapDate($mostRecentUpdatedAt), changefreq: 'weekly', priority: 0.9));
            foreach ($projects as $project) {
                array_push(
                    $urls,
                    $sm->sitemapUrl(
                        'app_work_detail',
                        $sm->formatSitemapDate($project->getUpdatedAt()),
                        changefreq: 'monthly',
                        parameters: ['slug' => $project->getSlug()]
                    )
                );
            }
        }

        // Blog
        array_push($urls, $sm->sitemapUrl('app_blog', $firstDayOfCurrentWeek, changefreq: 'weekly', priority: 0.9));

        $response = new Response($this->renderView('seo/sitemap.html.twig', ['urls' => $urls]), 200);
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
