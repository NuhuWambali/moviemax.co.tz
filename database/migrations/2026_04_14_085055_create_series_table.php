
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('genre')->nullable();
            $table->year('release_year')->nullable();
            $table->string('language')->default('English');
            $table->string('rating')->nullable();
            $table->unsignedBigInteger('price'); // Price for full series or per season
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->integer('seasons_count')->default(1);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('series');
    }
};