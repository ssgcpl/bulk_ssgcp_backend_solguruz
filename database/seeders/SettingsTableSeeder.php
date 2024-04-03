<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['name' => 'app_name'],
            ['value' => 'SSGC Bulk Order',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'app_short_name'],
            ['value' => 'SSGC Bulk Order',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'email_username'],
            ['value' => 'AKIAJ7FXIOXXE3ROZQLA',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'email_password'],
            ['value' => 'Aq+c90VvFahD2ufqzWJSOL52d3x7ZxeG4dg36CTdui3c',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'email_host'],
            ['value' => 'email-smtp.eu-west-1.amazonaws.com',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'email_port'],
            ['value' => '587',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'email_encryption'],
            ['value' => 'tls',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'email_from_address'],
            ['value' => 'info@ssgcp.com',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'email_from_name'],
            ['value' => 'SSGC Bulk Order',
            'status' => 'active']
        );

        Setting::updateOrCreate(
            ['name' => 'contact_email'],
            ['value' => 'ssgc_buk_order@gmail.com',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'customer_care_no'],
            ['value' => '1234567890',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'contact_address'],
            ['value' => '188ए/128, एलनगंज, चर्चलेन, इलाहाबाद-211002',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'company_logo'],
            ['value' => '',
            'status' => 'active'],
        );
        
        Setting::updateOrCreate(
            ['name' => 'working_hours'],
            ['value' => '12:00 PM to 08:00 PM (Mon to Fri)',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'currency'],
            ['value' => 'Rupee',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'currency_symbol'],
            ['value' => '₹',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'is_delivery_charges_applicable'],
            ['value' => 'on',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'amount_limit'],
            ['value' => '499',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'delivery_charges'],
            ['value' => '40',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'welcome_points'],
            ['value' => '30',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'refer_points'],
            ['value' => '10',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'referred_points'],
            ['value' => '12',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'wishlist_points'],
            ['value' => '10',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'wish_return_points'],
            ['value' => '10',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'points_per_rs'],
            ['value' => '10',
            'status' => 'active'],
        );

        //SMS GATEWAY
        Setting::updateOrCreate(
            ['name' => 'sms_gateway_url'],
            ['value' => 'https://api.msg91.com/api/',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'sms_gateway_authkey'],
            ['value' => '106360AYqjCGhBZnt6144a796P1',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'sms_gateway_sender'],
            ['value' => 'SSGCPL',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'sms_gateway_otp_flow_id'],
            ['value' => '608aa4637b0af1203216e213',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'sms_gateway_order_placed_flow_id'],
            ['value' => '62fceda08918ec193f60d515',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'sms_gateway_order_delivered_flow_id'],
            ['value' => '62fde2849db17803aa555598',
            'status' => 'active'],
        );


        Setting::updateOrCreate(
            ['name' => 'ccavenue_merchant_id'],
            ['value' => '114473',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'ccavenue_working_key'],
            ['value' => '872E0BDFAF6D553FFEA1831BA95C4A5B',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'ccavenue_access_code'],
            ['value' => 'AVCT04JI41AY79TCYA',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'ccavenue_mode'],
            ['value' => 'sandbox',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'ccavenue_sandbox_url'],
            ['value' => 'https://test.ccavenue.com/',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'ccavenue_production_url'],
            ['value' => 'https://secure.ccavenue.com/',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'payu_sandbox_key'],
            ['value' => 'oZ7oo9',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'payu_sandbox_salt'],
            ['value' => 'UkojH5TS',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'payu_live_key'],
            ['value' => 'Ed9uehay',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'payu_live_salt'],
            ['value' => 'b1jmcUbKjI',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'payu_mode'],
            ['value' => 'sandbox',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'payu_sandbox_url'],
            ['value' => 'https://test.payu.in/',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'payu_production_url'],
            ['value' => 'https://secure.payu.in/',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'order_return_address'],
            ['value' => 'lorem ipsum',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'order_return_contact_number'],
            ['value' => '12345678',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'facebook_url'],
            ['value' => 'http://facebook.com',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'twitter_url'],
            ['value' => 'https://twitter.com/',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'instagram_url'],
            ['value' => 'https://www.instagram.com/',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'telegram_url'],
            ['value' => 'https://web.telegram.org/k/',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'whatsapp_url'],
            ['value' => 'https://web.whatsapp.com/',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'wish_return_days_limit'],
            ['value' => '7',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'wishlist_days_limit'],
            ['value' => '7',
            'status' => 'active'],
        );

        Setting::updateOrCreate(
            ['name' => 'admin_email'],
            ['value' => 'booksubmission15@gmail.com', 
            'status' => 'active'],
        );  
        //Version Control
        Setting::updateOrCreate(
            ['name' => 'android_app_version'],
            ['value' => '1.0.0',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'android_app_version_update_type'],
            ['value' => 'soft', // soft/hard
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'apple_app_version'],
            ['value' => '1.0.0',
            'status' => 'active'],
        );
        Setting::updateOrCreate(
            ['name' => 'apple_app_version_update_type'],
            ['value' => 'soft', // soft/hard
            'status' => 'active'],
        );
	Setting::updateOrCreate(
            ['name' => 'payu_job_delay_in_seconds'],
            ['value' => '60',
            'status' => 'active'],
        );             



    }
}
