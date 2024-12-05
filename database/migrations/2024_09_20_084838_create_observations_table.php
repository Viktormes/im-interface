<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->bigInteger('version_id')->unsigned()->default(1);
            $table->timestamp('last_updated');
            $table->enum('status', ['created', 'updated', 'deleted', 'restored']);
            $table->jsonb('resource');
        });

        Schema::create('observations_history', function (Blueprint $table) {
            $table->ulid('id');
            $table->bigInteger('version_id')->unsigned();
            $table->timestamp('last_updated');
            $table->enum('status', ['created', 'updated', 'deleted', 'restored']);
            $table->jsonb('resource');

            $table->primary(['id', 'version_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observations_history');
        Schema::dropIfExists('observations');
    }
};
