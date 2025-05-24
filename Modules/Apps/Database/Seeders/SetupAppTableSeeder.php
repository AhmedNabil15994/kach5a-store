<?php

namespace Modules\Apps\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Area\Database\Seeders\SeedAreaModule;
use Modules\User\Entities\User;

class SetupAppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        Model::unguard();
        (new SeedAreaModule())->run();
        (new SeedAreaModule())->run();

        $this->insertUserRole($this->insertUser());
        DB::commit();
    }

    private function insertUser()
    {
        return User::create([
            'name' => 'Admin Ahmed',
            'mobile' => '96565656580',
            'email' => 'admin@tocaan.com',
            'password' => "Tocaan#1470",
        ]);
    }

    private function insertUserRole($user)
    {
        $user->assignRole(['super-admin']);
    }
}
