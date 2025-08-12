<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = array('Descargar consulta', 'Descargar todos', 'Descargar estudios con imagenes', 'Descargar ninguno', 'Bien común');
        foreach ($permissions as $permision) {
            if (Permission::where('name', $permision)->count() == 0) {
                Permission::create(['name' => $permision]);
            }
        }
    }
}
