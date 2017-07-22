<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguageCodeToMainMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_meta', function (Blueprint $table) {
            $table->string('language_code', 10)->nullable()->default('en');
            $table->foreign('language_code')->references('iso_code')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_meta', function (Blueprint $table) {
            $table->dropForeign('main_meta_language_code_foreign');
            $table->dropColumn('language_code');
        });
    }
}
