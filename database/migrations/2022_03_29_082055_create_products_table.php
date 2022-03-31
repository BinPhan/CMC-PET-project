<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->comment('Phân loại sản phẩm');
            $table->string('name')->comment('Tên sản phẩm');
            $table->string('description')->comment('Mô tả sản phẩm');
            $table->integer('features')->nullable()->comment('Định nghĩa xem sản phẩm có được lên sản phẩm nổi bật, bán chạy, hot... không');
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
        Schema::dropIfExists('products');
    }
}
