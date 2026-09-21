<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('parents')->nullOnDelete();
            $table->string('section')->nullable()->after('class_name');
            $table->index(['parent_id', 'class_name', 'section']);
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id', 'class_name', 'section']);
            $table->dropColumn(['parent_id', 'section']);
        });
    }
};
