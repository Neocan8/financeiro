<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saidas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedbigInteger('conta_id');
            $table->unsignedbigInteger('categoria_id');
            $table->string('nome', 50)->default('Nome da Entrada');
            $table->text('descricao')->nullable();
            $table->float('valor');
            $table->integer('parcela')->default(1);
            $table->string('id_referencia');
            $table->boolean('confirmado')->default(false);
            $table->date('data');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('conta_id')->references('id')->on('contas');
            $table->foreign('categoria_id')->references('id')->on('contas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saidas');
    }
}
