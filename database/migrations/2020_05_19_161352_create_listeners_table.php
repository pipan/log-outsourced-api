<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListenersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listeners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')
                ->unique();
            $table->unsignedBigInteger('project_id');
            $table->string('name', 255);
            $table->string('handler_slug', 255);
            $table->json('handler_values');
            $table->timestamps();

            $table->index(['project_id'], 'link_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listeners');
    }
}
