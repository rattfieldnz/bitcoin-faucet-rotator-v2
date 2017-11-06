<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100)->unique();
            $table->string('slug', 255)->unique();
            $table->string('summary', 255);
            $table->mediumText('content');
            $table->string('keywords', 255)->nullable();
            $table->integer('alert_type_id', false, true);
            $table->foreign('alert_type_id')
                ->references('id')
                ->on('alert_types');
            $table->integer('alert_icon_id', false, true);
            $table->foreign('alert_icon_id')
                ->references('id')
                ->on('alert_icons');
            $table->boolean('hide_alert')
                ->default(true);
            $table->boolean('show_site_wide')
                ->default(false);
            $table->boolean('show_only_on_home_page')
                ->default(false);
            $table->boolean('sent_with_twitter')->nullable()
                ->default(false);
            $table->timestamp('publish_at')->nullable()
                ->useCurrent();
            $table->timestamp('hide_at')->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('alerts');
        Schema::enableForeignKeyConstraints();
    }
}
