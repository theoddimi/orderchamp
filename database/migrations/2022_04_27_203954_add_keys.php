<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('cart', function($table)
        {
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::table('cart_product', function($table)
        {
            $table->index('cart_id');
            $table->index('product_id');
            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
        });
        
        Schema::table('discount_user_token', function($table)
        {
            $table->index('user_id');
            $table->index('discount_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discount')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('cart', function($table)
        {
         $table->dropForeign(['user_id']);
         $table->dropForeign(['guest_id']);
        });
        
        Schema::table('cart_product', function($table)
        {
         $table->dropForeign(['cart_id']);
         $table->dropForeign(['product_id']);
        });
        
        Schema::table('discount_user_token', function($table)
        {
         $table->dropForeign(['user_id']);
         $table->dropForeign(['discount_id']);
        });
    }
};
