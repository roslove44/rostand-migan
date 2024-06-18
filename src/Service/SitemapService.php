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
        $firstDayOfWeek = $today->modify('-' . ($dayOfWeek - 1) . ' days');
        return $this->formatSitemapDate($firstDayOfWeek);
    }

    public function sitemapUrl(string $routeName, string $date, float $priority = 0.8, string $changefreq = 'weekly'): array
    {
        return [
            'loc' => $this->generateUrl($routeName),
            'lastmod' => $date,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    private function generateUrl(string $routeName, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->router->generate($routeName, $parameters, $referenceType);
    }
    private function formatSitemapDate(\DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d\TH:i:sP');
    }
}
