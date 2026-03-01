<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->string('watermark_border_paud_img')->nullable()->after('watermark_border_img');
        });
    }

    public function down()
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->dropColumn('watermark_border_paud_img');
        });
    }
};
