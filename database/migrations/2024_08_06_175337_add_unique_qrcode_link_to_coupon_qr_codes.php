<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueQrcodeLinkToCouponQrCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_qr_codes', function (Blueprint $table) {
            $table->string('unique_qrcode_link')->nullable()->after('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_qr_codes', function (Blueprint $table) {
            $table->dropColumn('unique_qrcode_link');
        });
    }
}
