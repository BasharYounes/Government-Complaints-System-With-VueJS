<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'complaints',
            function (Blueprint $table) {

                $table->index(
                    [
                        'user_id',
                        'created_at',
                    ],
                    'complaints_user_created_at_index'
                );

                $table->index(
                    [
                        'user_id',
                        'status',
                    ],
                    'complaints_user_status_index'
                );
            }
        );
    }


    public function down(): void
    {
        Schema::table(
            'complaints',
            function (Blueprint $table) {

                $table->dropIndex(
                    'complaints_user_created_at_index'
                );

                $table->dropIndex(
                    'complaints_user_status_index'
                );
            }
        );
    }
};
