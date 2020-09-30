<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSiteSettings extends Migration
{

        public function up()
        {
            $this->forge->addField([
                'id'          => [
                        'type'           => 'INT',
                        'constraint'     => 5,
                        'unsigned'       => true,
                        'auto_increment' => true,
                        'null'           => false,
                ],
                'name'       => [
                        'type'           => 'VARCHAR',
                        'constraint'     => '255',
                ],
                'value' => [
                        'type'           => 'TEXT',
                        'null'           => false,
                ],
                'type' => [
                        'type'           => 'ENUM',
                        'constraint'     => ['general', 'advanced'],
                        'default'        => 'general',
                        'null'           => false,
                ]   
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('settings');
        }

        public function down()
        {
                $this->forge->dropTable('settings');
        }
}