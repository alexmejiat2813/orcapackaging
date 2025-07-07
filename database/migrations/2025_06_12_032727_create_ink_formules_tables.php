<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ink_formules', function (Blueprint $table) {
            $table->id();
            $table->string('final_color');
            $table->float('total_kg');
            $table->unsignedBigInteger('created_by_user_id');
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('ink_formule_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ink_formule_id');
            $table->unsignedInteger('product_id');
            $table->float('amount_kg');
            $table->float('percentage');
            $table->timestamps();

            $table->foreign('ink_formule_id')->references('id')->on('ink_formules')->onDelete('cascade');
            $table->foreign('product_id')->references('Product_ID')->on('Product')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ink_formule_details');
        Schema::dropIfExists('ink_formules');
    }
};
