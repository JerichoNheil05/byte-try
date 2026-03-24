<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSellerAgreementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'seller_agreement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'seller_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
                'unique' => true
            ],
            'agreement_version' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => '1.0',
                'null' => false,
                'comment' => 'Terms version agreed to'
            ],
            'terms_accepted' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false
            ],
            'terms_accepted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'terms_accepted_ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true
            ],
            'terms_accepted_user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'accepted', 'declined', 'revoked'],
                'default' => 'pending',
                'null' => false
            ],
            'agreement_data' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Full agreement text and changes'
            ],
            'decline_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'If declined, why'
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

        $this->forge->addKey('seller_agreement_id', true);
        $this->forge->addKey('seller_id');
        $this->forge->addKey(['status', 'created_at']);

        // Foreign keys removed - add separately if needed

        $this->forge->createTable('seller_agreements');
    }

    public function down()
    {
        $this->forge->dropTable('seller_agreements');
    }
}
