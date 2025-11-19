<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TomatoPHP\FilamentCms\Models\Category;
use TomatoPHP\FilamentEcommerce\Models\Product;
use TomatoPHP\FilamentEcommerce\Models\ProductMeta;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Category::truncate();
        ProductMeta::truncate();
        Schema::enableForeignKeyConstraints();

        // Create "Medical Equipment" category
        $medicalEquipmentCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Medical Equipment')],
            [
                'name' => 'Medical Equipment',
                'for'  => 'product', 'type' => 'category', 'color' => '#007bff'
            ]
        );

        // Create "Medical Consumables" category
        $medicalConsumablesCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Medical Consumables')],
            ['name' => 'Medical Consumables', 'for' => 'product', 'type' => 'category', 'color' => '#28a745']
        );

        $medicalEquipmentProducts = [
            'Electrophoresis (Genotype machine)',
            'Suction machine (double & single bottle)',
            'Stream sterilizer (18, 75, & 100 litres)',
            'Blood roller mixer',
            'Standiometer',
            'Hematocrit centrifuge (manual and digital, 12 and 8 buckets)',
            'Microscope',
            'Diathermy machine',
            'ECG machine',
            'Operating Lamp',
            'Water Bath (12 & 22L)',
            'Lab Oven (30)',
            'Lab incubator (35, 55L, & 20L)',
            'Centrifuge low speed (8* 15ml)',
            'Centrifuge low speed (12* 5ml)',
            'ECG Machine (12 channel)',
            'Autoclave (18 liters with tap, new model)',
            'Suction machine (new model)',
            'Patient Monitor stand',
            'Electrophoresis Machine',
            'Pen torch led',
            'Nursing watch',
            'Binocular Microscope',
            'Mobile theater lamp (LED-700)',
            'Hospital ward screen',
            'Weighing scale with height',
            'Oxygen regulator (side type)',
            'Centrifuge (8 bucket)',
            'Centrifuge (manual 12)',
            'Roller Mixer',
            'Haematocrit Centrifuge',
            'Hospital Overbed Table (blue)',
            'Hospital Overbed Table (white)',
            'Drip stand',
            'Wardscreen (model 005)',
        ];

        foreach ($medicalEquipmentProducts as $productName) {
            $product = Product::firstOrCreate(
                ['name' => $productName],
                [
                    'slug'               => \Illuminate\Support\Str::slug($productName),
                    'sku'                => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(10)),
                    'barcode'            => \Illuminate\Support\Str::random(12),
                    'price'              => rand(100, 1000) * 100, // Example price
                    'discount'           => rand(50, 900) * 100, // Example discount price
                    'vat'                => 0,
                    'description'        => 'A high-quality '.strtolower($productName).' for medical use.',
                    'about'              => 'Essential medical equipment.',
                    'is_activated'       => true,
                    'type'               => 'product',
                    'category_id'        => $medicalEquipmentCategory->id
                ]
            );

            // Add a meta description
            ProductMeta::firstOrCreate(
                ['product_id' => $product->id, 'key' => 'description'],
                ['value' => 'Detailed description for '.$productName]
            );
        }

        $medicalConsumablesProducts = [
            'Urine container',
            'Stool container',
            'Microscope slides (frosted)',
            'Microscope slides (plain)',
            'Vacuum Tube EDTA (10ml PET)',
            'Vacuum Tube EDTA (5ml PET)',
            'Vacuum Tube (plain)',
            'Petri Dish',
            'Cryovial Tube (2ml)',
            'Disposable Vaginal Speculum (L, M, S)',
            'ESR Tube',
            'Coverslips (22x22, 22x30, 22x40, 22x50)',
        ];

        foreach ($medicalConsumablesProducts as $productName) {
            $product = Product::firstOrCreate(
                ['name' => $productName],
                [
                    'slug'               => \Illuminate\Support\Str::slug($productName),
                    'sku'                => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(10)),
                    'barcode'            => \Illuminate\Support\Str::random(12),
                    'price'              => rand(5, 50) * 100, // Example price
                    'discount'           => rand(2, 40) * 100, // Example discount price
                    'vat'                => 0,
                    'description'        => 'A high-quality '.strtolower($productName).' for medical use.',
                    'is_activated'       => true,
                    'type'               => 'product',
                    'category_id'        => $medicalConsumablesCategory->id
                ]
            );

            // Add a meta description
            ProductMeta::firstOrCreate(
                ['product_id' => $product->id, 'key' => 'description'],
                ['value' => 'Detailed description for '.$productName]
            );
        }
    }
}
