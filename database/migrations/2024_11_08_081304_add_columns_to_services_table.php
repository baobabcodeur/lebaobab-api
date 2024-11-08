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
            $table->string('imgFile')->nullable()->after('description');
            $table->decimal('price', 10, 2)->after('imgFile');
            $table->foreignId('freelancerId')->nullable()->constrained('users')->onDelete('set null')->after('price');
            $table->enum('type', ['en ligne', 'presentiel'])->default('en ligne')->after('freelancerId');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            //
            $table->dropColumn('imgFile');
            $table->dropColumn('price');
            $table->dropForeign(['freelancerId']);
            $table->dropColumn('freelancerId');
            $table->dropColumn('type');
        });
    }
};
