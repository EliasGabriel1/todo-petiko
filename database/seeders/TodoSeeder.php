<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('todo_types')->insert([
            [
                'name' => 'Trabalho',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Estudos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pessoal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('todos')->insert([
            [
                'todo_type_id' => 1,
                'title' => 'Finalizar API BFF',
                'description' => 'Criar adapter do auth-service',
                'due_date' => Carbon::now()->addDays(2),
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'todo_type_id' => 2,
                'title' => 'Estudar Laravel Events',
                'description' => 'Aprender listeners e jobs',
                'due_date' => Carbon::now()->addDays(5),
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'todo_type_id' => 3,
                'title' => 'Treinar na academia',
                'description' => 'Treino de peito e tríceps',
                'due_date' => Carbon::now()->addDay(),
                'is_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}