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
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->string('imgFile')->nullable()->after('status'); // Image (facultatif)
            $table->decimal('budget', 10, 2)->nullable()->after('imgFile'); // Budget (facultatif)
            $table->enum('type', ['en ligne', 'presentiel'])->default('en ligne')->after('budget'); // Type de projet (en ligne ou présentiel)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->dropColumn(['imgFile', 'budget', 'type']);
        });
    }
};
