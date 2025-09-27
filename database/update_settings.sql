-- Create settings table if it doesn't exist
CREATE TABLE IF NOT EXISTS settings (
  setting_id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NOT NULL,
  description TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default WhatsApp contact if it doesn't exist
INSERT IGNORE INTO settings (setting_key, setting_value, description) 
VALUES ('vet_whatsapp_number', '2348012345678', 'WhatsApp number for veterinary contact (include country code without +)');

-- Update users table to make password optional
ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NULL;

-- Make sure admin user exists
INSERT IGNORE INTO users (username, password, email, full_name, role) 
VALUES ('admin', NULL, 'admin@example.com', 'System Administrator', 'admin');
