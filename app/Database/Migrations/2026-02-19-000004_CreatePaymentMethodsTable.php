<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentMethodsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'payment_method_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false
            ],
            'payment_type' => [
                'type' => 'ENUM',
                'constraint' => ['gcash', 'maya', 'paypal', 'bank_transfer', 'credit_card', 'ewallet'],
                'null' => false
            ],
            'account_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Account holder name'
            ],
            'account_number_encrypted' => [
                'type' => 'LONGTEXT',
                'null' => false,
                'comment' => 'AES-256 encrypted account/card number'
            ],
            'account_number_masked' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'Masked version (XXXX-XXXX-3456) for display'
            ],
            'is_default' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
                'comment' => 'Primary payment method'
            ],
            'is_verified' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
                'comment' => 'Payment method verified'
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Additional payment method data'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'on update' => 'CURRENT_TIMESTAMP'
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('payment_method_id', true);
        $this->forge->addKey(['user_id', 'is_default']);
        $this->forge->addKey(['user_id', 'deleted_at']);

        // Foreign keys removed - add separately if needed

        $this->forge->createTable('payment_methods');
    }

    public function down()
    {
        $this->forge->dropTable('payment_methods');
    }
}
