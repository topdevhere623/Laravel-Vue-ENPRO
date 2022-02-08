<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroundingTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'grounding';

    /**
     * Run the migrations.
     * @table grounding
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('groundingtype');
            $table->unsignedBigInteger('groundtype');
            $table->unsignedBigInteger('towermaterial');
            $table->unsignedBigInteger('inearthmaterial');
            $table->unsignedBigInteger('priming');
            $table->unsignedBigInteger('identifiedobject');
            $table->integer('constructiontype')->nullable();
            $table->double('resistance')->nullable();
            $table->string('vertelectr', 255)->nullable();
            $table->integer('vertelectrn')->nullable();
            $table->string('electrdist', 255)->nullable();
            $table->string('horizconelectr', 255)->nullable();
            $table->string('horizelectr', 255)->nullable();
            $table->double('depth')->nullable();
            $table->string('project', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('groundingtype')
                ->references('id')->on('groundingtype')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('groundtype')
                ->references('id')->on('groundtype')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('towermaterial')
                ->references('id')->on('towermaterial')
                ->onDelete('no action')
                ->onUpdate('no action'); // ts в references('uid') заменил на id

            $table->foreign('inearthmaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('priming')
                ->references('id')->on('priming')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('identifiedobject')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Заземления'");
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
