<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Class AddSlugOnCategoriesTable
 */
class AddSlugOnCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::table('categories', function(Blueprint $table) {
            $table->string('slug');
        });

        $this->updateSlugs();

        Schema::table('categories', function(Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table('categories', function(Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    /**
     * @return void
     */
    private function updateSlugs(): void {
        $categories = DB::table('categories')->get(['id', 'title']);

        foreach ($categories as $category) {
            $slug = Str::slug($category->title . ' ' . $category->id);

            DB::table('categories')
                ->where('id', '=', $category->id)
                ->update(['slug' => $slug]);
        }
    }

}
