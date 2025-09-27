-- Create settings table for system-wide settings
CREATE TABLE IF NOT EXISTS settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default WhatsApp contact
INSERT INTO settings (setting_key, setting_value, description) 
VALUES ('vet_whatsapp_number', '2348012345678', 'WhatsApp number for veterinary contact (include country code without +)');
