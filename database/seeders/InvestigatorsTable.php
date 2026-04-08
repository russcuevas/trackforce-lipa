<?php

namespace Database\Seeders;

use App\Models\Investigator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InvestigatorsTable extends Seeder
{
    public function run(): void
    {
        // Admin account
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

        // Generate 14 more investigators
        for ($i = 1; $i <= 14; $i++) {
            Investigator::updateOrCreate(
                ['email' => "investigator{$i}@trackforce.lipa"],
                [
                    'badge_number' => 'INV' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'full_name'    => "Investigator {$i}",
                    'email'        => "investigator{$i}@trackforce.lipa",
                    'password'     => Hash::make('123456789'),
                    'status'       => 'Active',
                ]
            );
        }
    }
}
