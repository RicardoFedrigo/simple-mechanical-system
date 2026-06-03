-- ========================================================
-- CUSTOMER 1 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Alice Smith', '555-0101', 'alice.smith@email.com', 'Regular customer, likes synthetic oil.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'alice.smith@email.com'), 1, 'AAA-1111', 'Corolla', 2019, 'ENTERED');

-- ========================================================
-- CUSTOMER 2 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Bob Jones', '555-0102', 'bob.jones@email.com', 'AC needs checking.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'bob.jones@email.com'), 2, 'BBB-2222', 'Mustang', 2015, 'ENTERED');

-- ========================================================
-- CUSTOMER 3 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Charlie Brown', '555-0103', 'charlie.b@email.com', 'Squeaking noise when braking.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'charlie.b@email.com'), 3, 'CCC-3333', 'Civic', 2022, 'ENTERED');

-- ========================================================
-- CUSTOMER 4 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Diana Prince', '555-0104', 'diana.p@email.com', NULL);

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'diana.p@email.com'), 4, 'DDD-4444', 'Equinox', 2020, 'ENTERED');

-- ========================================================
-- CUSTOMER 5 & THEIR VEHICLES (Multi-car owner)
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Evan Wright', '555-0105', 'evan.w@email.com', 'Fleet owner, fleet discount applies.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES 
((SELECT id FROM customers WHERE email = 'evan.w@email.com'), 1, 'EEE-5555', 'RAV4', 2021, 'ENTERED'),
((SELECT id FROM customers WHERE email = 'evan.w@email.com'), 3, 'EEE-5556', 'Accord', 2018, 'ENTERED');

-- ========================================================
-- CUSTOMER 6 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Fiona Gallagher', '555-0106', 'fiona.g@email.com', 'Needs a quick oil change.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'fiona.g@email.com'), 2, 'FFF-6666', 'Explorer', 2017, 'ENTERED');

-- ========================================================
-- CUSTOMER 7 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('George Clark', '555-0107', 'george.c@email.com', 'Check engine light is on.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'george.c@email.com'), 5, 'GGG-7777', '3 Series', 2016, 'ENTERED');

-- ========================================================
-- CUSTOMER 8 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Hannah Abbott', '555-0108', 'hannah.a@email.com', NULL);

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'hannah.a@email.com'), 4, 'HHH-8888', 'Silverado', 2023, 'ENTERED');

-- ========================================================
-- CUSTOMER 9 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Ian Malcolm', '555-0109', 'ian.m@email.com', 'Suspension feels loose.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'ian.m@email.com'), 2, 'III-9999', 'F-150', 2015, 'ENTERED');

-- ========================================================
-- CUSTOMER 10 & THEIR VEHICLE
-- ========================================================
INSERT INTO customers (name, phone, email, notes) 
VALUES ('Julia Roberts', '555-0110', 'julia.r@email.com', 'Left headlight is out.');

INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) 
VALUES ((SELECT id FROM customers WHERE email = 'julia.r@email.com'), 1, 'JJJ-0000', 'Prius', 2019, 'ENTERED');