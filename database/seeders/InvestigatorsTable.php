<?php

namespace Database\Seeders;

use App\Models\Investigator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InvestigatorsTable extends Seeder
{
    public function run(): void
    {
        Investigator::updateOrCreate(
            ['email' => 'admin@trackforce.lipa'],
            [
                'badge_number' => 'ADMIN001',
                'full_name'    => 'Admin',
                'email'        => 'admin@trackforce.lipa',
                'password'     => Hash::make('123456789'),
                'status'       => 'Active',
            ]
        );
    }
}
