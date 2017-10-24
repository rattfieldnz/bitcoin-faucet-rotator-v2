<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_network_links', function (Blueprint $table) {
            $table->string('facebook_url', 255)->nullable()->unique();
            $table->string('twitter_url', 255)->nullable()->unique();
            $table->string('reddit_url', 255)->nullable()->unique();
            $table->string('google_plus_url', 255)->nullable()->unique();
            $table->string('youtube_url', 255)->nullable()->unique();
            $table->string('digg_url', 255)->nullable()->unique();
            $table->string('flickr_url', 255)->nullable()->unique();
            $table->string('instagram_url', 255)->nullable()->unique();
            $table->string('odnoklassniki_url', 255)->nullable()->unique();
            $table->string('pinterest_url', 255)->nullable()->unique();
            $table->string('stumbleupon_url', 255)->nullable()->unique();
            $table->string('tumblr_url', 255)->nullable()->unique();
            $table->string('vimeo_url', 255)->nullable()->unique();
            $table->string('vkontakte_url', 255)->nullable()->unique();
            $table->string('sinaweibo_url', 255)->nullable()->unique();
            $table->string('xing_url', 255)->nullable()->unique();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_network_links');
    }
}
