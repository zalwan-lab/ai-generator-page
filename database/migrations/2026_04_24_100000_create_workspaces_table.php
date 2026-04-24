<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 32)->default('brand');
            $table->string('emoji', 16)->nullable();
            $table->timestamps();

            $table->index(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_workspace_id')
                ->nullable()
                ->after('id')
                ->constrained('workspaces')
                ->nullOnDelete();
        });

        Schema::table('sales_pages', function (Blueprint $table) {
            $table->foreignId('workspace_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->cascadeOnDelete();
        });

        // Backfill: create a "Default" workspace per user and reassign existing pages.
        foreach (DB::table('users')->get() as $u) {
            $wsId = DB::table('workspaces')->insertGetId([
                'user_id' => $u->id,
                'name' => 'Default',
                'color' => 'brand',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->where('id', $u->id)->update(['current_workspace_id' => $wsId]);
            DB::table('sales_pages')->where('user_id', $u->id)->update(['workspace_id' => $wsId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_workspace_id');
        });
        Schema::table('sales_pages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('workspace_id');
        });
        Schema::dropIfExists('workspaces');
    }
};
