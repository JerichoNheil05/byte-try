<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_item_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'seller_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Seller who provided the product'
            ],
            'product_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Product title snapshot'
            ],
            'product_category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Category snapshot'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 1,
                'null' => false
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => [10, 2],
                'null' => false,
                'comment' => 'Price per unit at purchase'
            ],
            'discount_per_item' => [
                'type' => 'DECIMAL',
                'constraint' => [10, 2],
                'null' => false,
                'default' => 0
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'null' => false,
                'comment' => 'quantity * unit_price - discount'
            ],
            'item_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled', 'returned'],
                'default' => 'pending',
                'null' => false
            ],
            'seller_notes' => [
                'type' => 'TEXT',
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

        $this->forge->addKey('order_item_id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey(['seller_id', 'item_status']);
        $this->forge->addKey('product_id');

        // Foreign keys removed - add separately if needed

        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
