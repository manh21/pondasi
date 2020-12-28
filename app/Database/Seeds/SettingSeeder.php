<?php namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settings_data = array(
            [
                'id'       => 1,
                'name'     => 'site_name',
                'value'    => 'Pondasi.test',
                'type'     => 'general',
            ],
            [
                'id'       => 2,
                'name'     => 'site_title',
                'value'    => 'Pondasi',
                'type'     => 'general',
            ],
            [
                'id'       => 3,
                'name'     => 'site_description',
                'value'    => 'Pondasi CI4 untuk project selanjutnya',
                'type'     => 'general',
            ],
            [
                'id'       => 4,
                'name'     => 'site_logo',
                'value'    => '/favicon.ico',
                'type'     => 'general',
            ],
            [
                'id'       => 5,
                'name'     => 'site_icon',
                'value'    => '/favicon.ico',
                'type'     => 'general',
            ],
            [
                'id'       => 6,
                'name'     => 'gtag',
                'value'    => '',
                'type'     => 'general',
            ],
            [
                'id'       => 7,
                'name'     => 'disqus',
                'value'    => '',
                'type'     => 'general',
            ],
            [
                'id'       => 8,
                'name'     => 'maintenance',
                'value'    => '1',
                'type'     => 'general',
            ],
        );

        $this->db->table('settings')->emptyTable();

        // Batch Insert data
        $this->db->table('settings')->insertBatch($settings_data);

        // foreach ($settings_data as $value) {
        //     $setting_data = array(
        //         'id'    =>    $value['id'],
        //         'name'  =>    $value['name'],
        //         'value' =>    $value['value'],
        //         'type'  =>    $value['type'],
        //     );

        //     // Simple Queries
        //     // $this->db->query("INSERT INTO settings (name, value, type) VALUES(:name:, :value:, :type:)",
        //     //         $settings_data
        //     // );

        //     // Using Query Builder
        //     $this->db->table('settings')->insert($setting_data);
        // }
    }
}

?>