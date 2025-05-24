<?php

namespace Modules\User\Database\Seeders;


use Modules\Apps\Database\Seeders\SetupAppTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(SetupAppTableSeeder::class);

        // $this->call("OthersTableSeeder");
    }
}
