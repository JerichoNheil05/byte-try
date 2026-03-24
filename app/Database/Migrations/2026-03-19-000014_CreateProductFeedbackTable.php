<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductFeedbackTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('product_feedback')) {
            return;
        }

        $this->forge->addField([
            'feedback_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'rating' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default' => 5,
                'null' => false,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'on update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('feedback_id', true);
        $this->forge->addKey('product_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey(['product_id', 'user_id'], false, true);

        $this->forge->createTable('product_feedback');
    }

    public function down()
    {
        if ($this->db->tableExists('product_feedback')) {
            $this->forge->dropTable('product_feedback');
        }
    }
}
