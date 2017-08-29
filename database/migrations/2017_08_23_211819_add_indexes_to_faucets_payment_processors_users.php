<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToFaucetsPaymentProcessorsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faucets', function (Blueprint $table) {
            $table->unique(['id', 'slug']);
            $table->index(['name']);
            $table->index(['interval_minutes']);
            $table->index(['min_payout']);
            $table->index(['max_payout']);
            $table->index(['has_ref_program']);
            $table->index(['ref_payout_percent']);
            $table->index(['is_paused']);
        });

        Schema::table('payment_processors', function (Blueprint $table) {
            $table->unique(['id', 'slug']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['id', 'slug']);
            $table->index(['user_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faucets', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['interval_minutes']);
            $table->dropIndex(['min_payout']);
            $table->dropIndex(['max_payout']);
            $table->dropIndex(['has_ref_program']);
            $table->dropIndex(['ref_payout_percent']);
            $table->dropIndex(['is_paused']);
        });

        Schema::table('payment_processors', function (Blueprint $table) {
            $table->dropUnique(['id', 'slug']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['id', 'slug']);
            $table->dropIndex(['user_name']);
        });
    }
}
