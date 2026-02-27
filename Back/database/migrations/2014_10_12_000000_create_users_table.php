<?php

use App\Models\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');    
            $table->string('number')->unique();
            $table->string('email')->unique(); 
            $table->string('fcm_token')->nullable(); 
            $table->string('otp')->nullable();
            $table->string('password_otp')->nullable();
            $table->timestamp('email_verified_at')->nullable(); 
            $table->string('password');
            $table->enum('type',['U', 'A', 'D'])->default('U');
            $table->foreignIdFor(Location::class, 'location_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
