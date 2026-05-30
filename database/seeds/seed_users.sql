-- Seed users for initial login
-- Passwords: admin@example.com = password123, mechanic@example.com = mechanic123, attendant@example.com = attendant123
INSERT INTO users (name, email, password_hash, role)
VALUES
  ('Admin User', 'admin@example.com', '$2y$10$PUWPGrtDuMdMFXDFNFoIe.sF92L.1qiAjXwXA2VmJtKOWcGfC8nD2', 'Admin'),
  ('Mechanic User', 'mechanic@example.com', '$2y$10$aX9WajzbjKIjeROuMJVRqOnWryXW1qrSHVkVve8evWL7BwEzaeEHK', 'Mechanic'),
  ('Attendant User', 'attendant@example.com', '$2y$10$4THWYND6wYdxh7lMdmr8tePKdDPbdsyp.w6M4thi6MLTR7Xjhaf/2', 'Attendant')
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password_hash = VALUES(password_hash),
  role = VALUES(role);
