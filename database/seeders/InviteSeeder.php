<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invite;

class InviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Invite::factory(100)->create();
    }
}
