<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول المهمات الرئيسية
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade'); // المسؤول
            $table->enum('status', ['active', 'completed', 'archived'])->default('active');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['creator_id', 'status']);
            $table->index('leader_id');
        });

        // جدول أعضاء المجموعة
        Schema::create('mission_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mission_id')->constrained('missions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['leader', 'member'])->default('member');
            $table->boolean('can_create_tasks')->default(true); // صلاحية إنشاء المهام
            $table->boolean('can_view_all_tasks')->default(false); // صلاحية رؤية كل المهام
            $table->timestamps();
            
            // منع التكرار
            $table->unique(['mission_id', 'user_id']);
            $table->index(['mission_id', 'role']);
        });

        // جدول المهام
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mission_id')->constrained('missions')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            // خصوصية المهمة
            $table->boolean('is_private')->default(false);
            
            // العلاقات
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            // التواريخ
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // الترتيب
            $table->integer('order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // الفهارس لتحسين الأداء
            $table->index(['mission_id', 'assigned_to', 'status']);
            $table->index(['creator_id', 'is_private']);
            $table->index('priority');
        });

        // جدول المهام المشتركة (للمهام العامة المخصصة لأكثر من شخص)
        Schema::create('task_assignees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['task_id', 'user_id']);
            $table->index('user_id');
        });

        // جدول تعليقات المهام (اختياري - للتواصل)
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
            
            $table->index('task_id');
        });

        // جدول مرفقات المهام (اختياري)
        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable(); // بالبايتات
            $table->timestamps();
            
            $table->index('task_id');
        });

        // جدول سجل تغييرات المهام (للتتبع)
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // created, updated, status_changed, assigned, etc.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
            
            $table->index(['task_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_logs');
        Schema::dropIfExists('task_attachments');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('task_assignees');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('mission_members');
        Schema::dropIfExists('missions');
    }
};