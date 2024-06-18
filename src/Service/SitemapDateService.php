<?php

namespace App\Service;

class SitemapDateService
{

    public function getFirstDayOfCurrentWeek()
    {
        $today = new \DateTimeImmutable();
        $dayOfWeek = $today->format('N');
        $firstDayOfWeek = $today->modify('-' . ($dayOfWeek - 1) . ' days');
        return $firstDayOfWeek->format('Y-m-d\TH:i:sP');
    }
}
