<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'transaction_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'transaction_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'unique' => true,
                'comment' => 'TXN-xxx reference number'
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Payer user ID'
            ],
            'payment_method_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => 3,
                'default' => 'PHP',
                'null' => false
            ],
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['payment', 'refund', 'adjustment', 'charge_back'],
                'null' => false
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'processing', 'completed', 'failed', 'cancelled'],
                'default' => 'pending',
                'null' => false
            ],
            'gateway_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Payment gateway transaction ID'
            ],
            'gateway_response' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Full gateway response data'
            ],
            'failure_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Why transaction failed if failed'
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When transaction was processed'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'comment' => 'IPv4 or IPv6'
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Browser user agent'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'on update' => 'CURRENT_TIMESTAMP'
            ]
        ]);

        $this->forge->addKey('transaction_id', true);
        $this->forge->addKey('transaction_reference');
        $this->forge->addKey(['order_id', 'status']);
        $this->forge->addKey(['user_id', 'status']);
        $this->forge->addKey('created_at');

        // Foreign keys removed - add separately if needed

        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
