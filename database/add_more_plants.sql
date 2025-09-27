-- Add 15 more Nigerian plants with their details

-- Plant 1: Moringa
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Moringa', 'Moringa oleifera', 'Vegetables', 'Moringa is a fast-growing, drought-resistant tree native to the Indian subcontinent but widely cultivated in Nigeria. Its leaves, pods, and seeds are highly nutritious and used for food, medicine, and water purification.', 'Nigeria', 'Easy', 'images/plants/moringa.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Well-drained loamy soil', 'Full sun to partial shade', '25-35°C', 'Moderate', 'Rainy season (April-June)', '1-2 cm deep for seeds, 30-50 cm for cuttings', '3-5 meters between trees');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering until established, then once a week during dry periods', 'Moderate', 'Moringa is drought-tolerant once established. Avoid waterlogging as it can cause root rot.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply organic compost or manure at planting and every 3-4 months.', 'Prune regularly to encourage bushy growth and easy harvesting. Cut back to 1-2 meters height annually.', 'Monitor for caterpillars and aphids. Use neem oil spray for control.', 'Ensure good air circulation and avoid waterlogging to prevent fungal diseases.', 'Harvest leaves regularly to encourage new growth. Trees can be coppiced (cut back severely) and will regrow rapidly.');

-- Plant 2: Bitter Leaf
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Bitter Leaf', 'Vernonia amygdalina', 'Vegetables', 'Bitter leaf is a perennial shrub native to tropical Africa. Its leaves are widely used in Nigerian cuisine, particularly in soups and stews, and have numerous medicinal properties.', 'Nigeria', 'Easy', 'images/plants/bitter_leaf.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Rich, well-drained soil', 'Full sun to partial shade', '20-35°C', 'Moderate to high', 'Early rainy season (March-May)', '15-20 cm deep for stem cuttings', '1-1.5 meters between plants');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering during establishment, then twice a week', 'Moderate', 'Keep soil consistently moist but not waterlogged. Reduce watering during rainy season.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply organic manure or NPK fertilizer (15-15-15) every 3 months.', 'Prune regularly to maintain shape and encourage new growth. Cut back severely once a year.', 'Monitor for aphids and mealybugs. Use insecticidal soap if infestation occurs.', 'Ensure good air circulation to prevent fungal diseases. Remove and destroy diseased leaves.', 'Harvest outer leaves regularly, leaving inner ones to continue growing. Plants can live for many years with proper care.');

-- Plant 3: Scent Leaf (Nchanwu)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Scent Leaf', 'Ocimum gratissimum', 'Herbs', 'Scent leaf, also known as Nchanwu or African basil, is an aromatic herb widely used in Nigerian cuisine for its distinctive flavor. It also has medicinal properties and is used to treat various ailments.', 'Nigeria', 'Easy', 'images/plants/scent_leaf.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Rich, well-drained soil', 'Full sun to partial shade', '20-35°C', 'Moderate to high', 'Rainy season (April-June)', '0.5-1 cm deep for seeds, 10-15 cm for cuttings', '30-45 cm between plants');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'Moderate', 'Keep soil consistently moist but not waterlogged. Water at the base to avoid wetting the leaves.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply balanced organic fertilizer monthly during growing season.', 'Pinch off flower buds to encourage leaf production. Prune regularly to maintain bushiness.', 'Monitor for aphids and whiteflies. Use neem oil spray for control.', 'Ensure good air circulation to prevent fungal diseases. Avoid overhead watering.', 'Harvest leaves regularly to encourage new growth. Plants can be grown as perennials in frost-free areas.');

-- Plant 4: Ugu (Fluted Pumpkin)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Ugu (Fluted Pumpkin)', 'Telfairia occidentalis', 'Vegetables', 'Ugu or fluted pumpkin is a tropical vine grown primarily for its nutritious leaves, which are a staple in Nigerian cuisine. The seeds are also edible and highly nutritious.', 'Nigeria', 'Moderate', 'images/plants/ugu.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Rich, well-drained loamy soil', 'Full sun', '25-35°C', 'Moderate to high', 'Early rainy season (March-April)', '3-5 cm deep for seeds', '1-1.5 meters between plants, 2 meters between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'Moderate to high', 'Keep soil consistently moist, especially during flowering and fruiting. Reduce watering during rainy season.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost before planting. Top dress with NPK fertilizer (15-15-15) after 4-6 weeks.', 'Train vines on trellises or stakes. Prune to control growth if necessary.', 'Monitor for aphids, leaf beetles, and caterpillars. Use neem oil or insecticidal soap for control.', 'Ensure good air circulation to prevent fungal diseases. Practice crop rotation.', 'Provide support for climbing. Harvest leaves regularly, leaving some for continued growth. Seeds take 4-5 months to mature.');

-- Plant 5: Garden Egg (African Eggplant)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Garden Egg', 'Solanum aethiopicum', 'Vegetables', 'Garden egg, or African eggplant, is a small, egg-shaped fruit widely consumed in Nigeria. It is typically eaten raw, boiled, or used in stews and soups.', 'Nigeria', 'Moderate', 'images/plants/garden_egg.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Well-drained, fertile loamy soil', 'Full sun', '20-32°C', 'Moderate', 'Early rainy season (March-April)', '0.5-1 cm deep for seeds, 15 cm for seedlings', '60-75 cm between plants, 75-90 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'Moderate', 'Keep soil consistently moist, especially during flowering and fruiting. Avoid wetting the leaves to prevent diseases.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost before planting. Side-dress with NPK fertilizer (15-15-15) when plants start flowering.', 'Remove suckers and lower leaves to improve air circulation. Stake plants if necessary.', 'Monitor for aphids, whiteflies, and fruit borers. Use neem oil or insecticidal soap for control.', 'Practice crop rotation. Remove and destroy diseased plants. Avoid overhead watering.', 'Harvest fruits when they are firm and glossy, usually 70-90 days after planting. Regular harvesting encourages more fruit production.');

-- Plant 6: Zobo (Roselle)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Zobo (Roselle)', 'Hibiscus sabdariffa', 'Herbs', 'Zobo, also known as Roselle or Sorrel, is grown for its colorful calyces which are used to make a popular Nigerian beverage called Zobo drink. The leaves are also edible and used in soups.', 'Nigeria', 'Easy', 'images/plants/zobo.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Well-drained, sandy loam soil', 'Full sun', '25-35°C', 'Moderate', 'Early rainy season (April-May)', '1-2 cm deep', '75-100 cm between plants, 100-150 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering until established, then once a week', 'Moderate', 'Zobo is relatively drought-tolerant once established. Ensure soil is moist during flowering and calyx formation.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost before planting. Side-dress with NPK fertilizer (15-15-15) after 4-6 weeks.', 'Pinch young plants to encourage branching. No major pruning required.', 'Monitor for aphids, whiteflies, and caterpillars. Use neem oil or insecticidal soap for control.', 'Ensure good air circulation to prevent fungal diseases. Remove and destroy diseased plants.', 'Harvest calyces when fully developed but still tender, usually 3-4 weeks after flowering. Seeds can be saved for next planting.');

-- Plant 7: Utazi (Gongronema)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Utazi', 'Gongronema latifolium', 'Herbs', 'Utazi is a climbing perennial vine with bitter-tasting leaves that are widely used as a spice in Nigerian cuisine, particularly in soups and stews. It also has numerous medicinal properties.', 'Nigeria', 'Moderate', 'images/plants/utazi.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Rich, well-drained soil', 'Partial shade to full sun', '25-32°C', 'Moderate to high', 'Rainy season (April-June)', '10-15 cm deep for stem cuttings', '1-1.5 meters between plants');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'Moderate', 'Keep soil consistently moist but not waterlogged. Reduce watering during rainy season.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply organic manure or compost at planting and every 3-4 months.', 'Train vines on trellises or stakes. Prune to control growth and encourage bushiness.', 'Monitor for aphids and mealybugs. Use neem oil or insecticidal soap for control.', 'Ensure good air circulation to prevent fungal diseases. Remove and destroy diseased leaves.', 'Provide support for climbing. Harvest leaves regularly to encourage new growth. Plants can live for many years with proper care.');

-- Plant 8: Uziza
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Uziza', 'Piper guineense', 'Herbs', 'Uziza is a climbing perennial vine known for its pungent, spicy leaves and berries that are used as a spice in Nigerian cuisine. Both the leaves and fruits have medicinal properties.', 'Nigeria', 'Moderate', 'images/plants/uziza.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Rich, well-drained soil with high organic matter', 'Partial shade', '25-32°C', 'High', 'Rainy season (April-June)', '10-15 cm deep for stem cuttings', '2-3 meters between plants');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'Moderate to high', 'Keep soil consistently moist. Uziza thrives in humid conditions, so mist leaves occasionally in dry weather.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply organic manure or compost at planting and every 3-4 months.', 'Train vines on trellises or stakes. Prune to control growth and encourage bushiness.', 'Monitor for aphids, mealybugs, and scale insects. Use neem oil or insecticidal soap for control.', 'Ensure good air circulation to prevent fungal diseases. Remove and destroy diseased leaves.', 'Provide support for climbing. Uziza prefers a forest-like environment, so plant near trees if possible. Harvest leaves and berries as needed.');

-- Plant 9: Waterleaf
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Waterleaf', 'Talinum triangulare', 'Vegetables', 'Waterleaf is a succulent, fast-growing leafy vegetable widely consumed in Nigerian cuisine, particularly in soups and stews. It is highly nutritious and easy to grow.', 'Nigeria', 'Easy', 'images/plants/waterleaf.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Well-drained, sandy loam soil', 'Full sun to partial shade', '25-35°C', 'Moderate to high', 'Rainy season (April-June)', '0.5-1 cm deep for seeds, 5-10 cm for stem cuttings', '20-30 cm between plants, 30-45 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 3-4 times per week', 'Moderate to high', 'Keep soil consistently moist. As the name suggests, waterleaf requires plenty of water to thrive.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost before planting. Side-dress with organic fertilizer monthly.', 'Pinch tips to encourage bushiness. No major pruning required.', 'Generally pest-resistant, but monitor for aphids and caterpillars. Use neem oil if necessary.', 'Ensure good air circulation to prevent fungal diseases. Remove and destroy diseased plants.', 'Harvest leaves regularly to encourage new growth. Waterleaf self-seeds readily and can become invasive if not controlled.');

-- Plant 10: Ewedu (Jute)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Ewedu', 'Corchorus olitorius', 'Vegetables', 'Ewedu, also known as jute mallow, is a leafy vegetable widely used in Nigerian cuisine, particularly in the western regions. It is used to prepare a slimy soup that is typically served with starchy foods.', 'Nigeria', 'Easy', 'images/plants/ewedu.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Well-drained, fertile soil', 'Full sun', '25-35°C', 'Moderate to high', 'Early rainy season (March-April)', '0.5-1 cm deep', '10-15 cm between plants, 30-45 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'Moderate', 'Keep soil consistently moist but not waterlogged. Reduce watering during rainy season.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost before planting. Side-dress with NPK fertilizer (15-15-15) after 3-4 weeks.', 'Pinch young plants to encourage branching. No major pruning required.', 'Monitor for aphids, flea beetles, and caterpillars. Use neem oil or insecticidal soap for control.', 'Ensure good air circulation to prevent fungal diseases. Practice crop rotation.', 'Harvest young leaves and shoots regularly to encourage new growth. Plants can be harvested 3-4 weeks after sowing.');

-- Plant 11: Cocoyam
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Cocoyam', 'Colocasia esculenta', 'Root Crops', 'Cocoyam is a starchy root crop widely cultivated in Nigeria. Both the corms (underground stems) and leaves are edible and used in various Nigerian dishes.', 'Nigeria', 'Moderate', 'images/plants/cocoyam.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Rich, well-drained loamy soil', 'Partial shade to full sun', '25-32°C', 'High', 'Early rainy season (March-April)', '5-10 cm deep for corms', '50-75 cm between plants, 75-100 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'High', 'Keep soil consistently moist. Cocoyam thrives in moist conditions but avoid waterlogging which can cause rot.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost before planting. Side-dress with NPK fertilizer (15-15-15) after 2-3 months.', 'Remove old and yellowing leaves to maintain plant health. No major pruning required.', 'Monitor for aphids, spider mites, and caterpillars. Use neem oil or insecticidal soap for control.', 'Ensure good air circulation to prevent fungal diseases. Practice crop rotation.', 'Mulch around plants to conserve moisture and suppress weeds. Harvest corms 6-8 months after planting when leaves start to yellow and die back.');

-- Plant 12: Sweet Potato
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Sweet Potato', 'Ipomoea batatas', 'Root Crops', 'Sweet potato is a starchy, sweet-tasting root crop widely cultivated in Nigeria. Both the tubers and leaves are edible and highly nutritious.', 'Nigeria', 'Easy', 'images/plants/sweet_potato.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Well-drained, sandy loam soil', 'Full sun', '20-30°C', 'Moderate', 'Early rainy season (March-April)', '10-15 cm deep for vine cuttings', '30-45 cm between plants, 75-100 cm between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering until established, then once a week', 'Moderate', 'Keep soil consistently moist during establishment. Sweet potato is drought-tolerant once established but needs water during tuber formation.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost before planting. Avoid excessive nitrogen which promotes vine growth at the expense of tubers.', 'No pruning required, but vines can be trained to prevent them from rooting at nodes.', 'Monitor for sweet potato weevils, caterpillars, and aphids. Use neem oil or insecticidal soap for control.', 'Practice crop rotation. Use disease-free planting material. Remove and destroy diseased plants.', 'Mound soil around the base of plants as they grow to increase tuber production. Harvest tubers 3-5 months after planting when leaves start to yellow.');

-- Plant 13: Plantain
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Plantain', 'Musa paradisiaca', 'Fruits', 'Plantain is a staple food crop in Nigeria, similar to banana but typically cooked before eating. It is a perennial herb with a pseudostem formed by leaf sheaths.', 'Nigeria', 'Moderate', 'images/plants/plantain.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Deep, rich, well-drained loamy soil', 'Full sun to partial shade', '20-35°C', 'High', 'Rainy season (April-June)', '30-45 cm deep for suckers', '3-4 meters between plants, 4-5 meters between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'High', 'Keep soil consistently moist. Plantains require plenty of water, especially during fruit development.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost at planting and every 3-4 months. Use potassium-rich fertilizer during fruiting.', 'Remove excess suckers, leaving only 3-4 per mat. Remove old and damaged leaves.', 'Monitor for banana weevils, nematodes, and aphids. Use appropriate insecticides if necessary.', 'Use disease-free planting material. Practice proper field sanitation. Remove and destroy diseased plants.', 'Mulch around plants to conserve moisture and suppress weeds. Support plants with stakes during fruiting to prevent toppling. Harvest when fruits are fully developed but still green, usually 10-12 months after planting.');

-- Plant 14: Pawpaw (Papaya)
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Pawpaw', 'Carica papaya', 'Fruits', 'Pawpaw, or papaya, is a fast-growing tree-like plant that produces sweet, orange-fleshed fruits. It is widely cultivated in Nigeria for its nutritious fruits and medicinal properties.', 'Nigeria', 'Easy', 'images/plants/pawpaw.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Well-drained, fertile loamy soil', 'Full sun', '25-35°C', 'Moderate', 'Early rainy season (March-April)', '1-2 cm deep for seeds, 15-20 cm for seedlings', '2-3 meters between plants, 3-4 meters between rows');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering, 2-3 times per week', 'Moderate', 'Keep soil consistently moist but not waterlogged. Reduce watering during rainy season.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost at planting and every 3-4 months. Use balanced NPK fertilizer (15-15-15) during fruiting.', 'Remove lower leaves as the plant grows taller. No major pruning required.', 'Monitor for fruit flies, mealybugs, and spider mites. Use appropriate insecticides if necessary.', 'Ensure good air circulation to prevent fungal diseases. Remove and destroy diseased plants.', 'Pawpaw is dioecious (separate male and female plants), so plant multiple seedlings to ensure fruiting. Thin to one plant per hole after sex identification. Harvest fruits when they show yellow patches, usually 9-12 months after planting.');

-- Plant 15: Mango
INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
VALUES ('Mango', 'Mangifera indica', 'Fruits', 'Mango is a popular fruit tree in Nigeria, known for its sweet, juicy fruits. It is a large, long-lived evergreen tree that can grow up to 30 meters tall.', 'Nigeria', 'Moderate', 'images/plants/mango.jpg');

INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
VALUES (LAST_INSERT_ID(), 'Deep, well-drained loamy soil', 'Full sun', '24-35°C', 'Moderate', 'Early rainy season (March-April)', '50-60 cm deep for saplings', '8-10 meters between trees');

INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
VALUES (LAST_INSERT_ID(), 'Regular watering until established, then once a week during dry season', 'Moderate to high', 'Water deeply but infrequently to encourage deep root growth. Reduce watering during rainy season. Water regularly during flowering and fruit development.');

INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
VALUES (LAST_INSERT_ID(), 'Apply well-rotted manure or compost annually. Use balanced NPK fertilizer (15-15-15) before flowering and during fruit development.', 'Prune young trees to establish a strong framework. Remove crossing, dead, and diseased branches. Prune after harvest.', 'Monitor for fruit flies, mealybugs, and scale insects. Use appropriate insecticides if necessary.', 'Ensure good air circulation to prevent fungal diseases. Avoid overhead irrigation.', 'Protect young trees from strong winds. Thin fruits if necessary to improve size and quality. Harvest when fruits are mature but still firm, usually 4-5 months after flowering. Trees start bearing fruit 3-5 years after planting.');

