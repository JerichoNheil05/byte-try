<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingProfileColumnsToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('users')) {
            return;
        }

        $fields = [];

        if (!$this->db->fieldExists('phone', 'users')) {
            $fields['phone'] = [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'bio',
            ];
        }

        if (!$this->db->fieldExists('country', 'users')) {
            $fields['country'] = [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'phone',
            ];
        }

        if (!$this->db->fieldExists('city', 'users')) {
            $fields['city'] = [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'country',
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('users')) {
            return;
        }

        if ($this->db->fieldExists('city', 'users')) {
            $this->forge->dropColumn('users', 'city');
        }

        if ($this->db->fieldExists('country', 'users')) {
            $this->forge->dropColumn('users', 'country');
        }

        if ($this->db->fieldExists('phone', 'users')) {
            $this->forge->dropColumn('users', 'phone');
        }
    }
}
