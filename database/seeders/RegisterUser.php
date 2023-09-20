<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RegisterUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array('administrador', 'medico', 'auxiliar', 'secretario');
        foreach ($roles as $roles) {
            Role::create(['name' => $roles]);
        }

        $data_user = array('name' => 'manuel',
        'email' => 'manuelsansoresg@gmail.com', 'password' => bcrypt('demor00txx'));
        $user = new User($data_user);
        $user->save();

        $user->assignRole('administrador');
        
    }
}
