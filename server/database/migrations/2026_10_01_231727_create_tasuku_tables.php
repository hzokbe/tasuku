<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('sessions');

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('username', 16)->unique();

            $table->string('email', 254)->unique();

            $table->string('password_hash', 60);

            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignUuid('user_id')->nullable()->index();

            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            $table->longText('payload');

            $table->integer('last_activity')->index();
        });

        Schema::create('lists', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title', 128);

            $table->text('description')->nullable();

            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title', 128);

            $table->text('description')->nullable();

            $table->enum('priority', ['low', 'medium', 'high']);

            $table->boolean('completed')->default(false);

            $table->foreignUuid('list_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title', 64);

            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('tag_task', function (Blueprint $table) {
            $table->foreignUuid('task_id')->constrained()->cascadeOnDelete();

            $table->foreignUuid('tag_id')->constrained()->cascadeOnDelete();

            $table->primary(['task_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_task');

        Schema::dropIfExists('tags');

        Schema::dropIfExists('tasks');

        Schema::dropIfExists('lists');

        Schema::dropIfExists('sessions');

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('email')->unique();

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            $table->rememberToken();

            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')->nullable()->index();

            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            $table->longText('payload');

            $table->integer('last_activity')->index();
        });
    }
};
