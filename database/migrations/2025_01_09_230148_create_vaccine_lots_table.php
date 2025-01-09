<?php

use App\Models\Vaccine;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vaccine_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vaccine::class)->constrained()->cascadeOnDelete();
            $table->string('lot_id');
            $table->date('expiration_date');
        });
    }

    /**
     * Run the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_lots');
    }
};
