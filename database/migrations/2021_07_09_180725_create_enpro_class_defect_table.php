<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnproClassDefectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enpro_class_defect', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('type')->nullable();
			$table->string('class')->nullable();
			$table->string('title')->nullable();
            $table->softDeletes();
            $table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('enpro_class_defect');
	}

}
