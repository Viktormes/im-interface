<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practitioners', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->bigInteger('version_id')->unsigned()->default(1);
            $table->timestamp('last_updated');
            $table->enum('status', ['created', 'updated', 'deleted', 'restored']);
            $table->jsonb('resource');
            $table->jsonb('phonetic')->nullable();
        });

        Schema::create('practitioners_history', function (Blueprint $table) {
            $table->ulid('id');
            $table->bigInteger('version_id')->unsigned();
            $table->timestamp('last_updated');
            $table->enum('status', ['created', 'updated', 'deleted', 'restored']);
            $table->jsonb('resource');
            $table->jsonb('phonetic')->nullable();

            $table->primary(['id', 'version_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practitioners_history');
        Schema::dropIfExists('practitioners');
    }
};
