INSERT INTO roles (name)
VALUES
  ('Admin'),
  ('Mechanic'),
  ('Attendant')
ON DUPLICATE KEY UPDATE
  name = VALUES(name);
