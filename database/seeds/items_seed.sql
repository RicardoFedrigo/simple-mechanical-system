INSERT INTO items (name, sku, quantity, unit_price) VALUES 
('Oil Filter', 'OF-001', 50, 15.50),
('Brake Pads', 'BP-002', 30, 45.00),
('Air Filter', 'AF-003', 40, 22.00),
('Spark Plug', 'SP-004', 100, 8.75),
('Battery 12V', 'BT-005', 10, 120.00),
('Coolant Fluid', 'CF-006', 20, 18.00),
('Wiper Blade', 'WB-007', 60, 12.50),
('Headlight Bulb', 'HB-008', 35, 9.99),
('Brake Fluid', 'BF-009', 25, 14.25),
('Oil Synthetic 5W30', 'OS-010', 100, 28.00)
ON DUPLICATE KEY UPDATE 
name = VALUES(name), 
quantity = VALUES(quantity), 
unit_price = VALUES(unit_price);
