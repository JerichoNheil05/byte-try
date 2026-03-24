<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpandProductPathColumns extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('products')) {
            return;
        }

        $fields = [];

        if ($this->db->fieldExists('file_path', 'products')) {
            $fields['file_path'] = [
                'name' => 'file_path',
                'type' => 'TEXT',
                'null' => true,
            ];
        }

        if ($this->db->fieldExists('preview_path', 'products')) {
            $fields['preview_path'] = [
                'name' => 'preview_path',
                'type' => 'TEXT',
                'null' => true,
            ];
        }

        if ($fields !== []) {
            $this->forge->modifyColumn('products', $fields);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('products')) {
            return;
        }

        $fields = [];

        if ($this->db->fieldExists('file_path', 'products')) {
            $fields['file_path'] = [
                'name' => 'file_path',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ];
        }

        if ($this->db->fieldExists('preview_path', 'products')) {
            $fields['preview_path'] = [
                'name' => 'preview_path',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ];
        }

        if ($fields !== []) {
            $this->forge->modifyColumn('products', $fields);
        }
    }
}