<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'order_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'Unique order reference (ORD-xxx)'
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Buyer user ID'
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false,
                'default' => 0
            ],
            'tax_amount' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false,
                'default' => 0,
                'comment' => 'Tax calculated at 12%'
            ],
            'tax_rate' => [
                'type' => 'DECIMAL',
                'constraint' => [5, 2],
                'null' => false,
                'default' => 12.00,
                'comment' => 'VAT/Tax rate percentage'
            ],
            'discount_amount' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false,
                'default' => 0
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false,
                'default' => 0,
                'comment' => 'Final amount to charge'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'processing', 'completed', 'cancelled', 'refunded'],
                'default' => 'pending',
                'null' => false,
                'comment' => 'Order status flow'
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['unpaid', 'processing', 'completed', 'failed', 'refunded'],
                'default' => 'unpaid',
                'null' => false
            ],
            'payment_method_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Type of payment method used'
            ],
            'tracking_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Shipping/Delivery tracking number'
            ],
            'delivery_address' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Encrypted delivery address'
            ],
            'buyer_notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Internal notes for admin'
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When order was completed'
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
                'null' => true,
                'comment' => 'Soft delete for compliance'
            ]
        ]);

        $this->forge->addKey('order_id', true);
        $this->forge->addKey('order_number', false, true); // Unique index
        $this->forge->addKey(['user_id', 'status']);
        $this->forge->addKey('payment_status');
        $this->forge->addKey('created_at');
        $this->forge->addKey('deleted_at');

        // No foreign keys - will add with separate migration if needed

        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
