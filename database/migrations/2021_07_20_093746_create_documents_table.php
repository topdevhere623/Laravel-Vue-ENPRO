<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('authorName');
            $table->string('comment');
            $table->dateTime('createdDateTime');
            $table->dateTime('lastModifiedDateTime');
            $table->string('revisionNumber');
            $table->string('subject');
            $table->string('title');
            $table->string('type');
            $table->foreignId('author')->nullable()->constrained('user')
                ->onUpdate('no action')->onDelete('no action');
            $table->foreignId('editor')->nullable()->constrained('user')
                ->onUpdate('no action')->onDelete('no action');
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
        Schema::dropIfExists('documents');
    }
}
