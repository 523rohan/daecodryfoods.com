-- Fix category_brands foreign key constraint
-- Drop the incorrect foreign key
ALTER TABLE `category_brands` DROP FOREIGN KEY `category_brands_brand_id_foreign`;

-- Add the correct foreign key
ALTER TABLE `category_brands` 
ADD CONSTRAINT `category_brands_brand_id_foreign` 
FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);
