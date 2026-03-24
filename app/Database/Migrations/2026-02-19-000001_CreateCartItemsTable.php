<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cart_item_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Buyer user ID'
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Product ID'
            ],
            'seller_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Product seller ID'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 1,
                'null' => false
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => [10, 2],
                'null' => false,
                'comment' => 'Product price at time of added to cart'
            ],
            'is_selected' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'comment' => 'Selected for checkout or not'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Buyer notes for this item'
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
                'comment' => 'Soft delete timestamp'
            ]
        ]);

        $this->forge->addKey('cart_item_id', true);
        $this->forge->addKey(['user_id', 'deleted_at']);
        $this->forge->addKey('product_id');
        $this->forge->addKey('seller_id');
        $this->forge->addKey('created_at');

        // Foreign keys removed - add separately if needed

        $this->forge->createTable('cart_items');
    }

    public function down()
    {
        $this->forge->dropTable('cart_items');
    }
}
