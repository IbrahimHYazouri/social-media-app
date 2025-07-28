<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropForeign('reactions_post_id_foreign');
            $table->renameColumn('post_id', 'object_id');
            $table->string('object_type')->after('object_id');
        });

        DB::table('reactions')->update(['object_type' => App\Models\Post::class]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            //
        });
    }
};
