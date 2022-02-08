<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPotentialTransformerInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('potential_transformer_info', function(Blueprint $table)
		{
			$table->foreign('asset_info_id', 'FK_pti_asset_info_id')->references('id')->on('asset_info')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('rated_frequency_id', 'FK_pti_frequency_id')->references('id')->on('frequency')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('enpro_climatic_mod_placement_id', 'FK_pti_gost_climatic_mod_placement_kind_id')->references('id')->on('gost_climatic_mod_placement_kind')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('enpro_construction_kind_id', 'FK_pti_potential_transformer_kind_id')->references('id')->on('potential_transformer_kind')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('enpro_secondary1_voltage_id', 'FK_pti_voltages_1')->references('id')->on('voltages')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('enpro_secondary2_voltage_id', 'FK_pti_voltages_2')->references('id')->on('voltages')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('rated_voltage_id', 'FK_pti_voltages_id')->references('id')->on('voltages')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('potential_transformer_info', function(Blueprint $table)
		{
			$table->dropForeign('FK_pti_asset_info_id');
			$table->dropForeign('FK_pti_frequency_id');
			$table->dropForeign('FK_pti_gost_climatic_mod_placement_kind_id');
			$table->dropForeign('FK_pti_potential_transformer_kind_id');
			$table->dropForeign('FK_pti_voltages_1');
			$table->dropForeign('FK_pti_voltages_2');
			$table->dropForeign('FK_pti_voltages_id');
		});
	}

}
