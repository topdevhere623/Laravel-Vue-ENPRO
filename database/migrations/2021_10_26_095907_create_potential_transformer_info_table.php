<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotentialTransformerInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('potential_transformer_info', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('asset_info_id')->unsigned()->nullable()->index('FK_pti_asset_info_id');
			$table->bigInteger('rated_voltage_id')->unsigned()->nullable()->index('FK_pti_voltages_id');
			$table->bigInteger('rated_frequency_id')->unsigned()->nullable()->index('FK_pti_frequency_id');
			$table->bigInteger('enpro_secondary1_voltage_id')->unsigned()->nullable()->index('FK_pti_voltages_1');
			$table->bigInteger('enpro_secondary2_voltage_id')->unsigned()->nullable()->index('FK_pti_voltages_2');
			$table->string('accuracyclass')->nullable();
			$table->bigInteger('enpro_construction_kind_id')->unsigned()->nullable()->index('FK_pti_potential_transformer_kind_id');
			$table->bigInteger('enpro_climatic_mod_placement_id')->unsigned()->nullable()->index('FK_pti_gost_climatic_mod_placement_kind_id');
			$table->float('massa')->nullable();
            $table->timestamps();
            $table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('potential_transformer_info');
	}

}
