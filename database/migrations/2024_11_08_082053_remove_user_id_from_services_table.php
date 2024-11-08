<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            //
               // Supprimer la clé étrangère si elle existe
               $table->dropForeign(['user_id']);

               // Ensuite, supprimer la colonne user_id
               $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            //
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }
};
