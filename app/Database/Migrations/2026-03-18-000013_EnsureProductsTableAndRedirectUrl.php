<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureProductsTableAndRedirectUrl extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('products')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'seller_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'price' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0,
                ],
                'category' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                ],
                'file_path' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'preview_path' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'redirect_url' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['active', 'hidden'],
                    'default' => 'active',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('seller_id');
            $this->forge->createTable('products');
            return;
        }

        if (!$this->db->fieldExists('redirect_url', 'products')) {
            $this->forge->addColumn('products', [
                'redirect_url' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'preview_path',
                ],
            ]);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('products')) {
            return;
        }

        if ($this->db->fieldExists('redirect_url', 'products')) {
            $this->forge->dropColumn('products', 'redirect_url');
        }
    }
}
