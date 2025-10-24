<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_relation_executive')->nullable()->after('created_by');
            // If you want to add a foreign key constraint, uncomment below:
            // $table->foreign('customer_relation_executive')->references('id')->on('employees')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('course_payments', function (Blueprint $table) {
            $table->dropColumn('customer_relation_executive');
        });
    }
};
