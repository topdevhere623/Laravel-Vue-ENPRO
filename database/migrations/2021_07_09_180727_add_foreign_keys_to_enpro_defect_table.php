<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEnproDefectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('enpro_defect', function(Blueprint $table)
		{
			$table->foreign('class_id', 'FK_enpro_defect_enpro_class_defect_id')->references('id')->on('enpro_class_defect')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('group_id', 'FK_enpro_defect_enpro_group_defect_id')->references('id')->on('enpro_group_defect')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('enpro_defect', function(Blueprint $table)
		{
			$table->dropForeign('FK_enpro_defect_enpro_class_defect_id');
			$table->dropForeign('FK_enpro_defect_enpro_group_defect_id');
		});
	}

}
