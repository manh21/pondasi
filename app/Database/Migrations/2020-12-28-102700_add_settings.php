<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSettings extends Migration
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
                // Primary Key
                $this->forge->addKey('id', TRUE);

                // More key
                $this->forge->addKey('name');
                
                $attributes = ['ENGINE' => 'InnoDB'];
                $this->forge->createTable('settings', TRUE, $attributes);
        }

        public function down()
        {
                $this->forge->dropTable('settings', TRUE);
        }
}