<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UrgentAd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;  // <-- Ajoutez ceci

class DeleteExpiredUrgentAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urgent-ads:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les annonces urgentes dont la date de validité est passée';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Exécute la commande.
     *
     * @return void
     */
    public function handle()
    {
        // Trouver les annonces dont la date de validité est passée
        $expiredAds = UrgentAd::where('valid_until', '<', Carbon::now())->get();

        foreach ($expiredAds as $ad) {
            // Supprimer les fichiers associés, s'il y en a
            if ($ad->file_path) {
                Storage::disk('public')->delete($ad->file_path);  // <-- Utilisation de Storage
            }
            // Supprimer l'annonce
            $ad->delete();
            $this->info("Annonce ID {$ad->id} supprimée.");
        }

        $this->info('Toutes les annonces expirées ont été supprimées.');
    }
}
