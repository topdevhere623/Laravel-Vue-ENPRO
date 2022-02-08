<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnproDefectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enpro_defect', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('code')->nullable();
			$table->bigInteger('class_id')->unsigned()->index('FK_enpro_defect_enpro_class_defect_id');
			$table->bigInteger('group_id')->unsigned()->index('FK_enpro_defect_enpro_group_defect_id');
			$table->string('title')->nullable();
			$table->integer('critical')->default(1);
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
		Schema::drop('enpro_defect');
	}

}
