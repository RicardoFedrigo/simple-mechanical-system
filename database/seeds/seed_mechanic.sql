-- Seed mechanic users and their mechanic profiles.
-- Password for all mechanic users: mechanic123

INSERT INTO users (name, email, password_hash, role_id)
VALUES
  ('Maria Santos', 'mechanic@example.com', '$2y$10$aX9WajzbjKIjeROuMJVRqOnWryXW1qrSHVkVve8evWL7BwEzaeEHK', (SELECT id FROM roles WHERE name = 'Mechanic')),
  ('Carlos Oliveira', 'mechanic2@example.com', '$2y$10$aX9WajzbjKIjeROuMJVRqOnWryXW1qrSHVkVve8evWL7BwEzaeEHK', (SELECT id FROM roles WHERE name = 'Mechanic')),
  ('Ana Pereira', 'mechanic3@example.com', '$2y$10$aX9WajzbjKIjeROuMJVRqOnWryXW1qrSHVkVve8evWL7BwEzaeEHK', (SELECT id FROM roles WHERE name = 'Mechanic'))
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password_hash = VALUES(password_hash),
  role_id = VALUES(role_id);

INSERT INTO mechanics (name, specialty, phone, user_id)
SELECT 'Maria Santos', 'Engine diagnostics', '555-0201', u.id
FROM users u
WHERE u.email = 'mechanic@example.com'
  AND NOT EXISTS (SELECT 1 FROM mechanics m WHERE m.user_id = u.id);

INSERT INTO mechanics (name, specialty, phone, user_id)
SELECT 'Carlos Oliveira', 'Brake systems', '555-0202', u.id
FROM users u
WHERE u.email = 'mechanic2@example.com'
  AND NOT EXISTS (SELECT 1 FROM mechanics m WHERE m.user_id = u.id);

INSERT INTO mechanics (name, specialty, phone, user_id)
SELECT 'Ana Pereira', 'Electrical systems', '555-0203', u.id
FROM users u
WHERE u.email = 'mechanic3@example.com'
  AND NOT EXISTS (SELECT 1 FROM mechanics m WHERE m.user_id = u.id);

UPDATE mechanics m
JOIN users u ON u.id = m.user_id
SET m.name = 'Maria Santos',
    m.specialty = 'Engine diagnostics',
    m.phone = '555-0201'
WHERE u.email = 'mechanic@example.com';

UPDATE mechanics m
JOIN users u ON u.id = m.user_id
SET m.name = 'Carlos Oliveira',
    m.specialty = 'Brake systems',
    m.phone = '555-0202'
WHERE u.email = 'mechanic2@example.com';

UPDATE mechanics m
JOIN users u ON u.id = m.user_id
SET m.name = 'Ana Pereira',
    m.specialty = 'Electrical systems',
    m.phone = '555-0203'
WHERE u.email = 'mechanic3@example.com';
