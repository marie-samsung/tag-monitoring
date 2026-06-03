<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\TagsModel;
use App\Models\VendorsModel;

class FixVendorIds extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'db:fix-vendor-ids';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Extracts vendor name from tags name parentheses and sets vendor_id.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'db:fix-vendor-ids';

    /**
     * Actually run the command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $tagsModel = new TagsModel();
        $vendorsModel = new VendorsModel();

        CLI::write("Fetching tags with vendor_id = 0...", "cyan");
        
        $tags = $tagsModel->where('vendor_id', 0)->findAll();
        $totalTags = count($tags);
        
        if ($totalTags === 0) {
            CLI::write("No tags found with vendor_id = 0.", "green");
            return;
        }

        CLI::write("Found {$totalTags} tags with vendor_id = 0. Processing...", "cyan");

        $updatedCount = 0;
        $createdVendors = [];

        foreach ($tags as $tag) {
            $tagName = $tag['name'];
            
            // Extract the vendor name from the first parenthesis
            if (preg_match('/\(s*(.*?)\s*\)/', $tagName, $matches)) {
                $vendorName = trim($matches[1]);
                
                if (empty($vendorName)) {
                    CLI::write("Tag ID {$tag['id']}: Extracted vendor name is empty from name: '{$tagName}'", "yellow");
                    continue;
                }

                // Check if vendor exists in database
                $vendor = $vendorsModel->where('name', $vendorName)->first();
                
                if ($vendor) {
                    $vendorId = $vendor['id'];
                } else {
                    // Create new vendor
                    CLI::write("Vendor '{$vendorName}' not found. Creating...", "yellow");
                    
                    $newVendorData = [
                        'name'       => $vendorName,
                        'is_active'  => 1,
                        'created_by' => 0,
                        'updated_by' => 0
                    ];
                    
                    $vendorId = $vendorsModel->insert($newVendorData, true);
                    
                    if (!$vendorId) {
                        CLI::write("Failed to create vendor '{$vendorName}' for tag ID {$tag['id']}.", "red");
                        continue;
                    }
                    
                    $createdVendors[] = "{$vendorName} (ID: {$vendorId})";
                }

                // Update the tag with the vendor_id
                $updateSuccess = $tagsModel->update($tag['id'], [
                    'vendor_id'  => $vendorId,
                    'updated_by' => 0
                ]);

                if ($updateSuccess) {
                    CLI::write("Tag ID {$tag['id']}: Mapped '{$tagName}' to vendor '{$vendorName}' (ID: {$vendorId})", "green");
                    $updatedCount++;
                } else {
                    CLI::write("Tag ID {$tag['id']}: Failed to update vendor_id to {$vendorId}.", "red");
                }
            } else {
                CLI::write("Tag ID {$tag['id']}: No parenthesis found in tag name '{$tagName}'", "yellow");
            }
        }

        CLI::write("\n--- Processing Summary ---", "cyan");
        CLI::write("Total tags processed: {$totalTags}", "yellow");
        CLI::write("Successfully updated: {$updatedCount}", "green");
        
        if (!empty($createdVendors)) {
            CLI::write("Newly created vendors: " . implode(', ', $createdVendors), "yellow");
        } else {
            CLI::write("No new vendors created.", "yellow");
        }
    }
}
