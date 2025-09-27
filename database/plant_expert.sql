-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS plant_expert;

-- Use the database
USE plant_expert;

-- Create users table for admin login
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, full_name, role) 
VALUES ('admin', '$2y$10$8KzO1f9XUUVrOe7Jh.JYz.X6Yl5ZGRQgF5WS0uXfZDRbYoKnB3GHe', 'admin@example.com', 'System Administrator', 'admin');

-- Create plants table
CREATE TABLE IF NOT EXISTS plants (
    plant_id INT AUTO_INCREMENT PRIMARY KEY,
    plant_name VARCHAR(100) NOT NULL,
    scientific_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    origin VARCHAR(100) NOT NULL,
    difficulty_level ENUM('Easy', 'Moderate', 'Difficult') NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- Create planting conditions table
CREATE TABLE IF NOT EXISTS planting_conditions (
    condition_id INT AUTO_INCREMENT PRIMARY KEY,
    plant_id INT NOT NULL,
    soil_type VARCHAR(100) NOT NULL,
    light_requirements VARCHAR(100) NOT NULL,
    temperature_range VARCHAR(50) NOT NULL,
    humidity_level VARCHAR(50) NOT NULL,
    planting_season VARCHAR(100) NOT NULL,
    planting_depth VARCHAR(50) NOT NULL,
    spacing VARCHAR(50) NOT NULL,
    FOREIGN KEY (plant_id) REFERENCES plants(plant_id) ON DELETE CASCADE
);

-- Create watering schedule table
CREATE TABLE IF NOT EXISTS watering_schedule (
    watering_id INT AUTO_INCREMENT PRIMARY KEY,
    plant_id INT NOT NULL,
    frequency VARCHAR(100) NOT NULL,
    amount VARCHAR(100) NOT NULL,
    special_instructions TEXT NOT NULL,
    FOREIGN KEY (plant_id) REFERENCES plants(plant_id) ON DELETE CASCADE
);

-- Create care instructions table
CREATE TABLE IF NOT EXISTS care_instructions (
    care_id INT AUTO_INCREMENT PRIMARY KEY,
    plant_id INT NOT NULL,
    fertilizing TEXT NOT NULL,
    pruning TEXT NOT NULL,
    pest_control TEXT NOT NULL,
    disease_prevention TEXT NOT NULL,
    special_care TEXT NOT NULL,
    FOREIGN KEY (plant_id) REFERENCES plants(plant_id) ON DELETE CASCADE
);

-- Create livestock table
CREATE TABLE IF NOT EXISTS livestock (
    livestock_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    origin VARCHAR(100) NOT NULL,
    lifespan VARCHAR(50) NOT NULL,
    size VARCHAR(50) NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- Create livestock care table
CREATE TABLE IF NOT EXISTS livestock_care (
    care_id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    daily_routine JSON NOT NULL,
    housing JSON NOT NULL,
    feeding JSON NOT NULL,
    FOREIGN KEY (livestock_id) REFERENCES livestock(livestock_id) ON DELETE CASCADE
);

-- Create livestock breeding table
CREATE TABLE IF NOT EXISTS livestock_breeding (
    breeding_id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    season VARCHAR(100) NOT NULL,
    gestation_period VARCHAR(50) NOT NULL,
    litter_size VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (livestock_id) REFERENCES livestock(livestock_id) ON DELETE CASCADE
);

-- Create livestock diseases table
CREATE TABLE IF NOT EXISTS livestock_diseases (
    disease_id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    disease_name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    prevention JSON NOT NULL,
    FOREIGN KEY (livestock_id) REFERENCES livestock(livestock_id) ON DELETE CASCADE
);

-- Insert 30 Nigerian plants with their details
-- Plant 1: Cassava
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Cassava', 'Manihot esculenta', 'Root Crops', 'Cassava is a woody shrub native to South America but widely cultivated in Nigeria. It is a major source of carbohydrates and its roots are processed into various food products.', 'Nigeria', 'Easy', 'images/plants/cassava.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (1, 'Well-drained sandy loam soil', 'Full sun', '25-29°C', 'Moderate to high', 'Early rainy season (March-May)', '5-10 cm deep', '1 meter apart');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (1, 'Once a week after planting until established, then relies on rainfall', 'Moderate', 'Cassava is drought-tolerant once established. Avoid waterlogging as it can cause root rot.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (1, 'Apply NPK fertilizer (15-15-15) at planting and again after 2-3 months.', 'No pruning required for root production.', 'Monitor for cassava mealybugs and green mites. Use neem oil spray for control.', 'Rotate crops to prevent buildup of soil-borne diseases. Use disease-free stem cuttings for planting.', 'Weed regularly, especially during the first 3 months. Harvest after 9-12 months when roots are mature.');

-- Plant 2: Yam
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Yam', 'Dioscorea spp.', 'Root Crops', 'Yam is a staple food in Nigeria with cultural significance. It produces starchy tubers that are a major source of carbohydrates.', 'Nigeria', 'Moderate', 'images/plants/yam.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (2, 'Deep, fertile, well-drained loamy soil', 'Full sun to partial shade', '25-30°C', 'Moderate to high', 'Beginning of rainy season (February-April)', '10-15 cm deep', '1 meter between plants, 1.5 meters between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (2, 'Regular watering during dry spells', 'Moderate', 'Ensure soil remains moist but not waterlogged. Reduce watering as harvest approaches.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (2, 'Apply well-rotted manure or compost before planting. Top dress with NPK fertilizer (15-15-15) after sprouting.', 'Train vines on stakes or trellises for better growth.', 'Watch for yam beetles and nematodes. Use appropriate insecticides if infestation is severe.', 'Use clean seed yams for planting. Practice crop rotation.', 'Mound the soil around the base as the plant grows. Provide support for climbing. Harvest when leaves turn yellow and begin to dry, usually 7-10 months after planting.');

-- Plant 3: Maize (Corn)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Maize', 'Zea mays', 'Grains', 'Maize is a cereal grain widely grown throughout Nigeria. It is used for human consumption, animal feed, and industrial purposes.', 'Nigeria', 'Easy', 'images/plants/maize.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (3, 'Well-drained, fertile loam soil', 'Full sun', '24-30°C', 'Moderate', 'Early rainy season (March-April or July-August)', '3-5 cm deep', '75 cm between plants, 75 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (3, 'Regular watering, especially during tasseling and ear formation', 'Moderate to high', 'Critical watering periods are during germination, tasseling, and grain filling stages.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (3, 'Apply NPK fertilizer (15-15-15) at planting and urea 4-5 weeks after planting.', 'No pruning required.', 'Monitor for fall armyworm, stem borers, and aphids. Use appropriate insecticides if necessary.', 'Use resistant varieties. Practice crop rotation.', 'Weed regularly, especially during early growth stages. Harvest when the husks turn brown and kernels are hard, usually 3-4 months after planting.');

-- Plant 4: Rice
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Rice', 'Oryza sativa', 'Grains', 'Rice is a major staple food in Nigeria, especially in southern regions. It is grown in paddy fields and requires significant water resources.', 'Nigeria', 'Difficult', 'images/plants/rice.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (4, 'Clay or clay loam soil with good water retention', 'Full sun', '20-35°C', 'High', 'Rainy season (May-June)', '2-3 cm deep', '20 cm between plants, 20 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (4, 'Maintain standing water of 5-10 cm during vegetative and reproductive stages', 'High', 'Drain field 2-3 weeks before harvest. Upland rice varieties require regular watering but not standing water.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (4, 'Apply NPK fertilizer (15-15-15) at planting and urea during tillering and panicle initiation stages.', 'No pruning required.', 'Monitor for rice stem borers, gall midge, and rice bugs. Use integrated pest management practices.', 'Use resistant varieties. Practice proper field sanitation.', 'Maintain proper water levels. Control weeds, especially during early growth stages. Harvest when 80-85% of grains are golden yellow, usually 3-4 months after planting.');

-- Plant 5: Okra
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Okra', 'Abelmoschus esculentus', 'Vegetables', 'Okra is a flowering plant valued for its edible seed pods. It is a common ingredient in Nigerian soups and stews.', 'Nigeria', 'Easy', 'images/plants/okra.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (5, 'Well-drained, fertile loam soil', 'Full sun', '22-35°C', 'Moderate to high', 'Early rainy season (March-April)', '1-2 cm deep', '45-60 cm between plants, 75-90 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (5, 'Regular watering, especially during flowering and fruiting', 'Moderate', 'Keep soil consistently moist but not waterlogged. Mulch to retain moisture.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (5, 'Apply well-rotted manure or compost before planting. Side-dress with NPK fertilizer (15-15-15) when plants are about 30 cm tall.', 'Remove old, diseased, or damaged leaves.', 'Monitor for aphids, whiteflies, and pod borers. Use neem oil spray for control.', 'Use resistant varieties. Avoid overhead watering to prevent fungal diseases.', 'Harvest pods when they are young and tender, usually 3-5 days after flowering. Regular harvesting encourages continued production.');

-- Continue with more plants (6-30)...

-- Insert 25 Nigerian livestock animals with their details
-- Livestock 1: Nigerian Dwarf Goat
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Nigerian Dwarf Goat', 'Small Ruminants', 'The Nigerian Dwarf Goat is a miniature dairy goat breed known for its small size and high milk production relative to its size. They are friendly, easy to handle, and adapt well to various environments.', 'Nigeria', '12-14 years', 'Small (40-60 cm at shoulder)', 'images/livestock/nigerian_dwarf_goat.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (1, 
'["Provide fresh water daily", "Check for signs of illness or distress", "Clean feeding areas", "Allow for grazing or exercise time", "Milk if being used for dairy production"]', 
'["Provide shelter from rain and sun", "Ensure good ventilation", "Allow at least 15 sq ft per goat", "Secure fencing at least 4 ft high", "Provide dry, clean bedding"]', 
'["Feed high-quality hay daily", "Provide goat-specific mineral supplements", "Offer small amounts of grain to lactating does", "Allow access to browse and forage", "Limit treats to prevent obesity"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (1, 'Year-round, but most fertile in fall', '145-153 days', '1-4 kids (typically 2)', 'Nigerian Dwarf Goats can breed year-round but are most fertile during fall months. Does come into heat every 18-24 days. Bucks should be separated from does except during breeding. First breeding should occur when doe reaches 70% of adult weight, typically at 7-8 months of age.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (1, 'Caprine Arthritis Encephalitis (CAE)', 'A viral disease affecting joints and sometimes the brain, causing arthritis and lameness in adult goats.', '["Test breeding stock", "Pasteurize milk for kids", "Maintain closed herd", "Practice good biosecurity"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (1, 'Caseous Lymphadenitis (CL)', 'A bacterial infection causing abscesses in lymph nodes and internal organs.', '["Avoid purchasing infected animals", "Isolate new animals", "Disinfect equipment", "Vaccinate if available"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (1, 'Enterotoxemia', 'Also known as overeating disease, caused by Clostridium perfringens bacteria producing toxins in the intestine.', '["Vaccinate", "Avoid sudden feed changes", "Provide adequate roughage", "Prevent overeating of grain"]');

-- Livestock 2: Sokoto Gudali Cattle
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Sokoto Gudali Cattle', 'Cattle', 'The Sokoto Gudali is a breed of zebu cattle native to Nigeria. They are known for their distinctive humps, pendulous dewlaps, and tolerance to heat and drought. They are primarily raised for meat and milk production.', 'Northern Nigeria', '15-20 years', 'Large (120-150 cm at shoulder)', 'images/livestock/sokoto_gudali.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (2, 
'["Provide fresh water daily", "Check health status", "Clean feeding and resting areas", "Allow for grazing time", "Milk cows if being used for dairy"]', 
'["Provide shelter from extreme weather", "Ensure adequate ventilation", "Allow at least 100 sq ft per animal", "Maintain clean, dry bedding", "Provide shade in hot weather"]', 
'["Allow grazing on quality pasture", "Supplement with hay during dry seasons", "Provide mineral blocks", "Feed concentrate to lactating cows and finishing cattle", "Ensure access to clean water at all times"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (2, 'Year-round, but often seasonal based on feed availability', '275-290 days', '1 calf (twins rare)', 'Sokoto Gudali cattle can breed year-round but calving is often timed to coincide with the rainy season when feed is abundant. Heifers should be bred when they reach 65-70% of mature weight, typically at 18-24 months. Bulls can service 25-30 cows in natural breeding systems.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (2, 'Foot and Mouth Disease', 'A highly contagious viral disease affecting cloven-hoofed animals, causing fever and blisters on the mouth and feet.', '["Vaccinate regularly", "Control movement of animals", "Quarantine new animals", "Practice good biosecurity"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (2, 'Contagious Bovine Pleuropneumonia', 'A highly contagious bacterial disease affecting the lungs and pleural cavity of cattle.', '["Vaccinate annually", "Test and slaughter infected animals", "Control animal movement", "Quarantine new animals"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (2, 'Trypanosomiasis', 'A parasitic disease transmitted by tsetse flies, causing anemia, weight loss, and eventually death if untreated.', '["Use insecticides to control tsetse flies", "Keep trypanotolerant breeds", "Prophylactic medication in high-risk areas", "Avoid grazing in tsetse-infested areas"]');

-- Livestock 3: Fulani Sheep
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Fulani Sheep', 'Small Ruminants', 'The Fulani sheep is a hardy breed adapted to the Sahel region of Nigeria. They are known for their long legs, thin tails, and ability to walk long distances. They are primarily raised for meat and occasionally for milk.', 'Northern Nigeria', '10-12 years', 'Medium (70-90 cm at shoulder)', 'images/livestock/fulani_sheep.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (3, 
'["Provide fresh water daily", "Monitor health and behavior", "Clean feeding areas", "Allow for grazing or exercise", "Check for parasites regularly"]', 
'["Provide simple shelter from rain and sun", "Ensure good ventilation", "Allow at least 10 sq ft per sheep", "Maintain secure fencing", "Provide dry bedding"]', 
'["Allow grazing on pasture when available", "Provide quality hay during dry seasons", "Supplement with minerals", "Feed concentrate to pregnant and lactating ewes", "Ensure access to salt blocks"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (3, 'Year-round, with peak fertility during rainy season', '145-155 days', '1-2 lambs', 'Fulani sheep can breed throughout the year but often show seasonal breeding patterns aligned with feed availability. Ewes come into heat every 16-17 days. First breeding should occur when ewe reaches about 70% of adult weight, typically at 10-12 months of age. Rams can service 30-35 ewes in a breeding season.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (3, 'Peste des Petits Ruminants (PPR)', 'A highly contagious viral disease affecting small ruminants, causing fever, pneumonia, and diarrhea.', '["Vaccinate annually", "Quarantine new animals", "Avoid contact with infected flocks", "Practice good biosecurity"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (3, 'Sheep Pox', 'A viral disease causing fever and skin lesions that can be fatal, especially in young animals.', '["Vaccinate", "Isolate infected animals", "Control insect vectors", "Practice good hygiene"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (3, 'Internal Parasites', 'Various worms that infect the digestive tract, causing weight loss, anemia, and reduced productivity.', '["Regular deworming", "Rotational grazing", "Avoid overcrowding", "Monitor fecal egg counts"]');

-- Continue with more livestock (4-25)...

-- Add more Nigerian plants and livestock as needed to reach the required numbers

