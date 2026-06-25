// database/migrations/2026_06_25_000001_add_lost_fine_and_notifications.php
// Replace the existing migration file with this

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('replacement_price', 8, 2)->default(0)->after('available_copies');
        });

        Schema::table('book_requests', function (Blueprint $table) {
            $table->boolean('is_lost')->default(false)->after('fine_status');
            $table->decimal('lost_fine_amount', 8, 2)->default(0)->after('is_lost');
        });

        Schema::create('member_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->boolean('read')->default(false);
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('replacement_price');
        });
        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropColumn(['is_lost', 'lost_fine_amount']);
        });
        Schema::dropIfExists('member_notifications');
    }
};