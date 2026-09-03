<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'notifications',
            function (Blueprint $table) {

                $table
                    ->string('type')
                    ->nullable()
                    ->after('user_id')
                    ->index();

                $table
                    ->json('data')
                    ->nullable()
                    ->after('body');

                $table->index(
                    [
                        'user_id',
                        'is_read',
                        'created_at',
                    ],
                    'notifications_user_read_created_index'
                );
            }
        );
    }


    public function down(): void
    {
        Schema::table(
            'notifications',
            function (Blueprint $table) {

                $table->dropIndex(
                    'notifications_user_read_created_index'
                );

                $table->dropColumn([
                    'type',
                    'data',
                ]);
            }
        );
    }
};
