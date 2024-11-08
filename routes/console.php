<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\DeleteExpiredUrgentAds;
use App\Models\UrgentAd;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('urgent-ads:delete-expired', function () {
    // Appel de la logique pour supprimer les annonces expirées
    $expiredAds = UrgentAd::where('valid_until', '<', now())->get();
    foreach ($expiredAds as $ad) {
        $ad->delete();
    }

    $this->info('Les annonces expirées ont été supprimées.');
})->describe('Supprimer les annonces expirées');