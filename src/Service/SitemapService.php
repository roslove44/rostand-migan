<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapService
{
    public function __construct(private UrlGeneratorInterface $router,)
    {
    }

    public function getFirstDayOfCurrentWeek(): string
    {
        $today = new \DateTimeImmutable();
        $dayOfWeek = $today->format('N');
        $firstDayOfWeek = $today->modify('-' . ($dayOfWeek - 1) . ' days')->setTime(5, 27, 45); // setTime bff juste pour garder fixe les secondes 
        return $this->formatSitemapDate($firstDayOfWeek);
    }

    public function sitemapUrl(string $routeName, string $date, float $priority = 0.8, string $changefreq = 'weekly', array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): array
    {
        return [
            'loc' => $this->generateUrl($routeName, $parameters, $referenceType),
            'lastmod' => $date,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    public function formatSitemapDate(\DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d\TH:i:sP');
    }

    private function generateUrl(string $routeName, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->router->generate($routeName, $parameters, $referenceType);
    }
}
