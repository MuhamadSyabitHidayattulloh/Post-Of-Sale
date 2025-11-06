<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('member')->index(); // admin|kasir|member
            $table->string('status')->default('active'); // active|inactive
            $table->foreignId('member_tier_id')->nullable()->constrained('tiers')->nullOnDelete();
            $table->unsignedInteger('points')->default(0);
            $table->string('phone')->nullable();
            $table->string('avatar_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('member_tier_id');
            $table->dropColumn(['role','status','points','phone','avatar_url']);
        });
    }
};
