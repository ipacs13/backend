<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get database driver to handle database-specific column types
        $driver = DB::getDriverName();

        // Create table using Laravel Schema Builder (database-agnostic)
        Schema::create('plan_type', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('description')->nullable();

            // Status column: use tinyInteger for MySQL/MariaDB, smallInteger for PostgreSQL
            // Both support values 0-1, PostgreSQL doesn't support unsigned but values are small
            if ($driver === 'mysql' || $driver === 'mariadb') {
                $table->tinyInteger('status')->unsigned()->default(1);
            } else {
                // PostgreSQL: use smallInteger (unsigned not supported, but values 0-1 are fine)
                $table->smallInteger('status')->default(1);
            }
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index('code');
            $table->index('name');
            $table->index('description');
            $table->index('status');
        });

        // Read SQL file to extract data
        $sqlPath = base_path('sql/cclpidb_agent.sql');
        $sqlContent = File::get($sqlPath);

        // Extract INSERT statement values - match pattern: values (row1),(row2),(row3);
        if (preg_match('/insert\s+into\s+[`"]?t_plan_type[`"]?\s*\([^)]+\)\s*values\s*(.+?);/is', $sqlContent, $insertMatch)) {
            $valuesString = trim($insertMatch[1]);

            // Split rows by ),( pattern (handles whitespace)
            $rowStrings = preg_split('/\)\s*,\s*\(/', $valuesString);

            $data = [];
            foreach ($rowStrings as $rowString) {
                // Remove parentheses
                $rowString = trim($rowString, '()');

                // Parse values: numbers and quoted strings
                // Pattern matches: 'string' or number
                preg_match_all("/(?:'((?:[^'\\\\]|\\\\.)*)'|(\d+))/", $rowString, $matches, PREG_SET_ORDER);

                $values = [];
                foreach ($matches as $match) {
                    if (isset($match[1]) && $match[1] !== '') {
                        // Quoted string
                        $values[] = stripcslashes($match[1]);
                    } elseif (isset($match[2]) && $match[2] !== '') {
                        // Number
                        $values[] = (int)$match[2];
                    }
                }

                // Ensure we have exactly 5 values: id, code, name, description, status
                if (count($values) === 5) {
                    $now = now();
                    $data[] = [
                        'id' => $values[0],
                        'code' => $values[1],
                        'name' => $values[2],
                        'description' => $values[3],
                        'status' => $values[4],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Insert data using database-agnostic method
            if (!empty($data)) {
                DB::table('plan_type')->insert($data);
            }
        }

        // Set auto-increment/sequence for next insert
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            // PostgreSQL: Set the sequence to start from 4
            DB::statement("SELECT setval('plan_type_id_seq', 3, true)");
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            // MySQL: Set AUTO_INCREMENT to 4
            DB::statement('ALTER TABLE plan_type AUTO_INCREMENT = 4');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_type');
    }
};
