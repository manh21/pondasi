<?php namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class InstallSeeder extends Seeder
{
    public function run()
    {
        $this->call('App\Database\Seeds\SettingSeeder');
        $this->call('IonAuth\Database\Seeds\IonAuthSeeder');
    }
}