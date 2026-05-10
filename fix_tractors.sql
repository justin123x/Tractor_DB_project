-- Update empty tractor statuses to 'available'
UPDATE `tractors` SET `status` = 'available' WHERE `status` = '' OR `status` IS NULL;