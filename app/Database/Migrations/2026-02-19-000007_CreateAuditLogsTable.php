<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'audit_log_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'User performing action, NULL for system'
            ],
            'action_type' => [
                'type' => 'ENUM',
                'constraint' => ['login', 'logout', 'profile_update', 'password_change', 'payment_method_add', 'payment_method_delete', 'order_create', 'order_cancel', 'product_upload', 'product_delete', 'terms_accepted', 'terms_declined', 'cashout_request', 'admin_action', 'data_access'],
                'null' => false
            ],
            'entity_type' => [
                'type' => 'ENUM',
                'constraint' => ['user', 'order', 'payment', 'product', 'transaction', 'wallet', 'notification', 'system'],
                'null' => false
            ],
            'entity_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID of affected entity'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => false
            ],
            'old_values' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Previous state of entity'
            ],
            'new_values' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'New state of entity'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['success', 'failed', 'warning'],
                'default' => 'success',
                'null' => false
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addKey('audit_log_id', true);
        $this->forge->addKey(['user_id', 'created_at']);
        $this->forge->addKey(['action_type', 'status']);
        $this->forge->addKey(['entity_type', 'entity_id']);
        $this->forge->addKey('created_at');

        // Foreign keys removed - add separately if needed

        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}
