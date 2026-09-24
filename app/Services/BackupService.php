<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BackupService
{
    public function tables(): array
    {
        $tables = [];

        if (DB::connection()->getDriverName() === 'sqlite') {
            foreach (DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'") as $row) {
                $tables[] = $row->name;
            }

            return $tables;
        }

        foreach (DB::select('SHOW TABLES') as $row) {
            $tables[] = array_values((array) $row)[0];
        }

        return $tables;
    }

    public function dump(): string
    {
        $data = [];
        foreach ($this->tables() as $table) {
            $data[$table] = DB::table($table)
                ->get()
                ->map(fn ($row) => (array) $row)
                ->all();
        }

        return (string) json_encode([
            'app' => 'supervisi-guru',
            'created_at' => now()->toDateTimeString(),
            'tables' => $data,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function restore(array $tables): void
    {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        if ($isSqlite) {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        try {
            foreach ($this->tables() as $table) {
                if ($table === 'sessions') {
                    continue;
                }

                DB::table($table)->truncate();
            }

            foreach ($tables as $table => $rows) {
                if ($table === 'sessions') {
                    continue;
                }

                foreach (array_chunk($rows, 500) as $chunk) {
                    DB::table($table)->insert($chunk);
                }
            }
        } finally {
            if ($isSqlite) {
                DB::statement('PRAGMA foreign_keys = ON');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        }
    }
}
