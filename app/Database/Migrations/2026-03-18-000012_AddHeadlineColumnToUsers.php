<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHeadlineColumnToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('users')) {
            return;
        }

        if (!$this->db->fieldExists('headline', 'users')) {
            $this->forge->addColumn('users', [
                'headline' => [
                    'type' => 'VARCHAR',
                    'constraint' => 200,
                    'null' => true,
                    'after' => 'profile_image',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users') && $this->db->fieldExists('headline', 'users')) {
            $this->forge->dropColumn('users', 'headline');
        }
    }
}
