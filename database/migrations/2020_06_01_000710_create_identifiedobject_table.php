<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentifiedobjectTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'identifiedobject';

    /**
     * Run the migrations.
     * @table identifiedobject
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('classname_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('subclass_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('voltage_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('asset_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('enobj_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('subcontrollarea_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('bay_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('role_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('connector_id')->nullable(); // ts добавил nullable()
            $table->string('keylink', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('localname', 255)->nullable()->comment('Диспетчерский номер');
            $table->string('name', 255)->nullable()->comment('Диспетчерское имя');
            $table->string('eqcid', 255)->nullable();
            $table->float('lat', 20, 17)->nullable()->comment('Широта'); // ts добавил nullable()
            $table->float('long', 20, 17)->nullable()->comment('Долгота'); // ts добавил nullable()
            $table->integer('entitytype')->nullable();
            $table->integer('group')->nullable()->comment('toDo ');
            $table->integer('phaseno')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('classname_id')
                ->references('id')->on('classname')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('subclass_id')
                ->references('id')->on('subclass')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('voltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('enobj_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('subcontrollarea_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('bay_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('role_id')
                ->references('id')->on('role')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('connector_id')
                ->references('id')->on('connector')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Общие технические данные IO'");
        } catch (Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
