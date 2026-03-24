<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWalletTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'wallet_transaction_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'seller_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Associated order if applicable'
            ],
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['sale', 'commission_deduction', 'withdrawal', 'refund', 'bonus', 'adjustment'],
                'null' => false
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false
            ],
            'transaction_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'unique' => true,
                'comment' => 'WAL-xxx reference'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['completed', 'pending', 'failed', 'cancelled'],
                'default' => 'completed',
                'null' => false
            ],
            'balance_before' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false,
                'comment' => 'Wallet balance before transaction'
            ],
            'balance_after' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false,
                'comment' => 'Wallet balance after transaction'
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true
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

        $this->forge->addKey('wallet_transaction_id', true);
        $this->forge->addKey('transaction_reference');
        $this->forge->addKey(['seller_id', 'created_at']);
        $this->forge->addKey('order_id');
        $this->forge->addKey('status');

        // Foreign keys removed - add separately if needed

        $this->forge->createTable('wallet_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('wallet_transactions');
    }
}
