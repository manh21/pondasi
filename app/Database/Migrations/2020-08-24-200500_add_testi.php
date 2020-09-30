<?php namespace App\Database\Migrations;
class AddTest extends \CodeIgniter\Database\Migration {
    public function up()
    {
        $this->forge->addField([
                'id'          => [
                        'type'           => 'INT',
                        'constraint'     => 5,
                        'unsigned'       => true,
                        'auto_increment' => true,
                ],
                'username' => [
                        'type'           => 'VARCHAR',
                        'constraint'     => '100',
                ],
                'fullname' => [
                        'type'           => 'TEXT',
                        'null'           => true,
                ],
                'email' => [
                        'type'           => 'TEXT',
                        'null'           => true,
                ],
        ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('test');
    }

    public function down()
    {
        $this->forge->dropTable('test');
    }
}

?>