<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->index();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->integer('speed')->nullable();
            $table->timestamp('time')->nullable();
            $table->timestamps();
        });
    }
}
