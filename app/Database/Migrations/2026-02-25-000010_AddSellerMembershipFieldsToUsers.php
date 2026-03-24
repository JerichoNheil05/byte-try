<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSellerMembershipFieldsToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('users')) {
            return;
        }

        $fields = [];

        if (!$this->db->fieldExists('account_type', 'users')) {
            $fields['account_type'] = [
                'type' => 'ENUM',
                'constraint' => ['buyer', 'seller', 'admin'],
                'default' => 'buyer',
                'null' => false,
                'after' => 'role',
            ];
        }

        if (!$this->db->fieldExists('subscription_status', 'users')) {
            $fields['subscription_status'] = [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'suspended', 'pending'],
                'default' => 'inactive',
                'null' => false,
                'after' => 'account_type',
            ];
        }

        if (!$this->db->fieldExists('membership_label', 'users')) {
            $fields['membership_label'] = [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'Inactive',
                'null' => false,
                'after' => 'subscription_status',
            ];
        }

        if (!$this->db->fieldExists('subscription_end_date', 'users')) {
            $fields['subscription_end_date'] = [
                'type' => 'DATE',
                'null' => true,
                'after' => 'membership_label',
            ];
        }

        if (!$this->db->fieldExists('seller_commission_rate', 'users')) {
            $fields['seller_commission_rate'] = [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => '10.00',
                'null' => false,
                'after' => 'subscription_end_date',
            ];
        }

        if (!$this->db->fieldExists('seller_dashboard_preferences', 'users')) {
            $fields['seller_dashboard_preferences'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'seller_commission_rate',
            ];
        }

        if (!$this->db->fieldExists('seller_store_profile', 'users')) {
            $fields['seller_store_profile'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'seller_dashboard_preferences',
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('users')) {
            return;
        }

        $dropColumns = [
            'account_type',
            'subscription_status',
            'membership_label',
            'subscription_end_date',
            'seller_commission_rate',
            'seller_dashboard_preferences',
            'seller_store_profile',
        ];

        foreach ($dropColumns as $column) {
            if ($this->db->fieldExists($column, 'users')) {
                $this->forge->dropColumn('users', $column);
            }
        }
    }
}
