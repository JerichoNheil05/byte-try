<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductFeatureAndHowItWorksColumns extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('products')) {
            return;
        }

        $columns = [];

        if (!$this->db->fieldExists('product_feature', 'products')) {
            $columns['product_feature'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description',
            ];
        }

        if (!$this->db->fieldExists('how_it_works', 'products')) {
            $columns['how_it_works'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'product_feature',
            ];
        }

        if (!empty($columns)) {
            $this->forge->addColumn('products', $columns);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('products')) {
            return;
        }

        if ($this->db->fieldExists('how_it_works', 'products')) {
            $this->forge->dropColumn('products', 'how_it_works');
        }

        if ($this->db->fieldExists('product_feature', 'products')) {
            $this->forge->dropColumn('products', 'product_feature');
        }
    }
}
