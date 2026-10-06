-- Starter data. Run after schema.sql:  mysql -u root shiftkoro < database/seed.sql
-- Trucks are NOT seeded here: they come from the Truck API (Admin → Trucks → Sync from API).

USE shiftkoro;

-- Default admin. Login: admin@shiftkoro.com / Admin@123  (change it after first login!)
INSERT INTO users (name, email, phone, password_hash, role) VALUES
('Administrator', 'admin@shiftkoro.com', '01700000000',
 '$2y$10$/wsXOY/F/0QgtprYZlJZt.DnXB73.tZaKaJZZLv9.v81H6rmC5XdO', 'admin');

-- Approximate coordinates of common pickup/destination areas.
INSERT INTO locations (name, city, latitude, longitude) VALUES
('Mirpur 1',          'Dhaka', 23.7957000, 90.3537000),
('Mirpur 10',         'Dhaka', 23.8069000, 90.3687000),
('Uttara',            'Dhaka', 23.8759000, 90.3795000),
('Gulshan',           'Dhaka', 23.7806000, 90.4167000),
('Banani',            'Dhaka', 23.7937000, 90.4066000),
('Bashundhara R/A',   'Dhaka', 23.8193000, 90.4526000),
('Badda',             'Dhaka', 23.7805000, 90.4267000),
('Rampura',           'Dhaka', 23.7612000, 90.4211000),
('Khilgaon',          'Dhaka', 23.7516000, 90.4259000),
('Dhanmondi',         'Dhaka', 23.7461000, 90.3742000),
('Mohammadpur',       'Dhaka', 23.7662000, 90.3589000),
('Farmgate',          'Dhaka', 23.7577000, 90.3896000),
('Tejgaon',           'Dhaka', 23.7639000, 90.3925000),
('Motijheel',         'Dhaka', 23.7330000, 90.4172000),
('Old Dhaka',         'Dhaka', 23.7085000, 90.4073000),
('Jatrabari',         'Dhaka', 23.7104000, 90.4349000),
('Keraniganj',        'Dhaka', 23.6985000, 90.3450000),
('Savar',             'Dhaka', 23.8583000, 90.2667000),
('Tongi',             'Gazipur', 23.8916000, 90.4023000),
('Narayanganj',       'Narayanganj', 23.6238000, 90.4990000),
('Agrabad',           'Chattogram', 22.3245000, 91.8122000),
('Zindabazar',        'Sylhet', 24.8949000, 91.8687000),
('Shaheb Bazar',      'Rajshahi', 24.3667000, 88.6000000),
('Khulna Sadar',      'Khulna', 22.8456000, 89.5403000);
