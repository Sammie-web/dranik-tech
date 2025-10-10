
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRejectedToUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('users')) {
			Schema::table('users', function (Blueprint $table) {
				if (!Schema::hasColumn('users', 'is_rejected')) {
					$table->boolean('is_rejected')->default(false)->after('is_active');
				}
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasTable('users')) {
			Schema::table('users', function (Blueprint $table) {
				if (Schema::hasColumn('users', 'is_rejected')) {
					$table->dropColumn('is_rejected');
				}
			});
		}
	}
}
