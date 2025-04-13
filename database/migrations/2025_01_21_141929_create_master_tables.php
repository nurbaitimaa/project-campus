<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterTables extends Migration
{
    public function up()
    {
        // Tabel User
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user'); // Primary Key
            $table->string('username', 50);
            $table->string('password', 255);
            $table->enum('role', ['admin', 'pimpinan']);
            $table->timestamps();
        });

        // Tabel Customer
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id_customer'); // Primary Key
            $table->string('nama_customer', 100);
            $table->string('telepon_customer', 15)->nullable();
            $table->text('alamat_customer')->nullable();
            $table->text('kota')->nullable();
            $table->text('region')->nullable();
            $table->timestamps();
        });

        // Tabel Tim Marketing
        Schema::create('marketing_teams', function (Blueprint $table) {
            $table->id('id_marketing'); // Primary Key
            $table->string('nama_marketing', 100);
            $table->string('telepon_marketing', 15)->nullable();
            $table->string('area', 50);
            $table->string('jabatan', 50);
            $table->timestamps();
        });

        // Tabel Program
        Schema::create('programs', function (Blueprint $table) {
            $table->id('id_program'); // Primary Key, Auto Increment
            $table->string('nama_program', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
        Schema::dropIfExists('marketing_teams');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('user');
    }
}