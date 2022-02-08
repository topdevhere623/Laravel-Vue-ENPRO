<?php


namespace App\Traits;


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreateTableDataTypeTrait
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->tableName)) return;
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('multiplier_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->float('value')->nullable();
            $table->foreign('multiplier_id')
                ->references('id')->on('unit_multiplier')
                ->onDelete('no action')
                ->onUpdate('no action');
            $table->foreign('unit_id')
                ->references('id')->on('unit_symbols')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }
}
