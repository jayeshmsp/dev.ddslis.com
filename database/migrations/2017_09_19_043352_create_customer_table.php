<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_number');
            $table->string('company_secret_key');
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_web_site');
            $table->string('billing_address_street');
            $table->string('billing_address_suite');
            $table->string('billing_address_city');
            $table->string('billing_address_state');
            $table->string('billing_address_zip_code');
            $table->string('contact_first_name');
            $table->string('contact_last_name');
            $table->string('contact_email_address');
            $table->string('contact_phone_number');
            $table->integer('contact_phone_type');
            $table->string('license_id');
            $table->integer('license_type');
            $table->date('license_start_date');
            $table->date('license_end_date');
            $table->enum('license_valid',array('0','1'))->default('0');
            $table->integer('maximum_licensed_users');
            $table->integer('deleted')->default(0);
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
        Schema::drop('customers');
    }
}
