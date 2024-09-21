<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('terminals', function (Blueprint $table) {

            // Add the 'city_id' column as a foreign key
            $table->unsignedBigInteger('city_id')->nullable()->after('id'); // Adjust the column order as necessary

            // Optionally, set up a foreign key constraint if there is a 'cities' table
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terminals', function (Blueprint $table) {
            // Add the 'location' column back (ensure you define the type)

            // Drop the 'city_id' column and foreign key constraint
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });
    }
};
