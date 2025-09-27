-- Add 15 more Nigerian livestock with their details, including fishes and poultry birds

-- Livestock 1: Broiler Chicken
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Broiler Chicken', 'Poultry', 'Broiler chickens are fast-growing meat birds raised specifically for meat production. They are characterized by their rapid growth rate, efficient feed conversion, and high meat yield.', 'Nigeria', '6-8 weeks (commercial lifespan)', 'Medium (2-4 kg at maturity)', 'images/livestock/broiler_chicken.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Check for signs of illness or distress", "Ensure proper ventilation", "Monitor feed consumption", "Clean feeding and drinking equipment"]', 
'["Provide well-ventilated housing with 0.1 sq m per bird", "Maintain clean, dry litter", "Ensure adequate lighting (23 hours for first week, then 16-18 hours)", "Protect from predators and extreme weather", "Maintain optimal temperature (32°C for chicks, gradually reducing to 21°C by week 4)"]', 
'["Feed starter diet (22-24% protein) for first 2-3 weeks", "Switch to grower diet (20-22% protein) until 4-5 weeks", "Finish with finisher diet (18-20% protein)", "Ensure constant access to feed", "Provide grit for proper digestion"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with proper management', 'Not applicable (eggs incubate for 21 days)', '50-200 eggs per year depending on breed', 'Broiler chickens are typically not bred on farms but purchased as day-old chicks from hatcheries. They reach market weight in 6-8 weeks and are not kept for breeding purposes. If breeding is desired, maintain a ratio of 1 rooster to 8-10 hens, provide proper nutrition, and collect eggs daily for incubation.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Newcastle Disease', 'A highly contagious viral disease affecting respiratory, nervous, and digestive systems. Symptoms include gasping, coughing, drooping wings, and twisted neck.', '["Vaccinate according to recommended schedule", "Maintain good biosecurity practices", "Control visitor access to poultry areas", "Quarantine new birds before introducing to flock"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Coccidiosis', 'A parasitic disease caused by protozoa affecting the intestinal tract. Symptoms include bloody diarrhea, weight loss, and decreased feed consumption.', '["Use coccidiostats in feed", "Maintain dry litter", "Practice good sanitation", "Avoid overcrowding", "Rotate birds on pasture if possible"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Infectious Bursal Disease (Gumboro)', 'A viral disease affecting the immune system of young chickens. Symptoms include whitish diarrhea, vent picking, and immunosuppression.', '["Vaccinate according to recommended schedule", "Maintain good biosecurity practices", "Provide clean water and feed", "Disinfect housing between flocks"]');

-- Livestock 2: Layer Chicken
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Layer Chicken', 'Poultry', 'Layer chickens are bred specifically for egg production. They are typically smaller than broilers and can produce up to 300 eggs per year under optimal conditions.', 'Nigeria', '1-3 years (commercial lifespan)', 'Small to Medium (1.5-2 kg at maturity)', 'images/livestock/layer_chicken.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Collect eggs at least twice daily", "Check for signs of illness or distress", "Ensure proper ventilation", "Clean feeding and drinking equipment"]', 
'["Provide well-ventilated housing with 0.15-0.2 sq m per bird", "Install nest boxes (1 per 4-5 hens)", "Provide perches (15-20 cm per bird)", "Ensure 14-16 hours of light daily for optimal egg production", "Protect from predators and extreme weather"]', 
'["Feed layer diet (16-18% protein) with adequate calcium (3.5-4%)", "Provide oyster shell or limestone as calcium supplement", "Ensure constant access to feed and water", "Provide grit for proper digestion", "Avoid sudden changes in diet"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with proper management and lighting', 'Not applicable (eggs incubate for 21 days)', '250-300 eggs per year depending on breed and management', 'Layer hens will produce eggs without a rooster present, but these eggs will be infertile. For breeding purposes, maintain a ratio of 1 rooster to 10-12 hens, provide proper nutrition with adequate protein and calcium, and collect eggs daily. Eggs for hatching should be stored pointed end down at 13-16°C for no more than 7 days before incubation.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Egg Drop Syndrome', 'A viral disease causing a sudden drop in egg production and poor egg quality with soft shells or shell-less eggs.', '["Vaccinate according to recommended schedule", "Maintain good biosecurity practices", "Quarantine new birds before introducing to flock", "Control wild birds and rodents"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Infectious Bronchitis', 'A highly contagious viral respiratory disease. Symptoms include gasping, coughing, sneezing, and reduced egg production with misshapen eggs.', '["Vaccinate according to recommended schedule", "Maintain good ventilation", "Practice good biosecurity", "Avoid overcrowding", "Control dust in housing"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Fowl Pox', 'A viral disease characterized by wart-like growths on unfeathered body parts and diphtheritic (wet) lesions in the mouth, throat, and respiratory tract.', '["Vaccinate according to recommended schedule", "Control mosquitoes and other biting insects", "Maintain good biosecurity practices", "Isolate affected birds"]');

-- Livestock 3: Guinea Fowl
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Guinea Fowl', 'Poultry', 'Guinea fowl are game birds native to Africa, known for their spotted feathers and distinctive calls. They are raised for both meat and egg production and are more disease-resistant than chickens.', 'Nigeria', '10-15 years', 'Medium (1.5-2 kg at maturity)', 'images/livestock/guinea_fowl.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Check for signs of illness or distress", "Ensure proper ventilation", "Allow for free-range foraging if possible", "Clean feeding and drinking equipment"]', 
'["Provide well-ventilated housing with 0.2-0.3 sq m per bird", "Install perches at different heights", "Ensure secure fencing as guinea fowl can fly", "Provide sheltered areas for protection from weather", "Maintain clean, dry bedding"]', 
'["Feed game bird or poultry feed (19-24% protein for keets, 16-18% for adults)", "Supplement with foraged insects and vegetation when possible", "Provide grit for proper digestion", "Ensure constant access to clean water", "Offer occasional treats like millet or sunflower seeds"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Seasonal, typically during rainy season (April-October)', 'Not applicable (eggs incubate for 26-28 days)', '80-100 eggs per year', 'Guinea fowl are seasonal breeders in Nigeria, with peak breeding during the rainy season. Maintain a ratio of 1 male to 5-7 females for breeding. Females will lay eggs in hidden nests on the ground, so provide secluded nesting areas. Guinea fowl are known for being poor sitters, so eggs are often collected and hatched under broody chickens or in incubators. Keets (young guinea fowl) require higher protein feed and warmer brooding temperatures than chicks.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Newcastle Disease', 'A highly contagious viral disease affecting respiratory, nervous, and digestive systems. Guinea fowl are more resistant than chickens but can still be affected.', '["Vaccinate according to recommended schedule", "Maintain good biosecurity practices", "Control visitor access to poultry areas", "Quarantine new birds before introducing to flock"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Helminth Infections', 'Internal parasitic worms that can cause weight loss, decreased egg production, and general unthriftiness.', '["Rotate pasture areas", "Deworm regularly based on veterinary advice", "Maintain clean housing and feeding areas", "Avoid overcrowding", "Provide clean water sources"]');

-- Livestock 4: Turkey
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Turkey', 'Poultry', 'Turkeys are large poultry birds raised primarily for meat. They are characterized by their large size, distinctive wattles, and ability to fan their tail feathers.', 'Nigeria (introduced)', '2-5 years (commercial lifespan 4-6 months)', 'Large (males 10-15 kg, females 5-8 kg at maturity)', 'images/livestock/turkey.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Check for signs of illness or distress", "Ensure proper ventilation", "Monitor feed consumption", "Clean feeding and drinking equipment"]', 
'["Provide well-ventilated housing with 0.5-1 sq m per bird", "Install strong perches for roosting", "Ensure adequate height for large birds", "Protect from predators and extreme weather", "Maintain clean, dry bedding"]', 
'["Feed starter diet (26-28% protein) for first 8 weeks", "Switch to grower diet (20-22% protein) until 16 weeks", "Finish with finisher diet (16-18% protein)", "Provide grit for proper digestion", "Ensure constant access to clean water"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Seasonal, typically during dry season (November-March)', 'Not applicable (eggs incubate for 28 days)', '30-90 eggs per year depending on breed', 'Turkeys in Nigeria typically breed during the dry season. Maintain a ratio of 1 tom (male) to 8-10 hens for breeding. Provide secluded nesting areas with clean bedding. Turkey hens are good sitters and will incubate their own eggs, though artificial incubation is common in commercial settings. Poults (young turkeys) require higher protein feed and careful brooding management, with temperatures starting at 35°C and gradually reducing.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Blackhead Disease (Histomoniasis)', 'A parasitic disease affecting the liver and cecum. Symptoms include droopiness, sulfur-colored droppings, and darkened head.', '["Avoid raising turkeys with chickens", "Practice good sanitation", "Use preventative medications as recommended by a veterinarian", "Rotate pastures", "Control earthworms which can carry the parasite"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Fowl Cholera', 'A bacterial disease causing fever, reduced appetite, respiratory distress, and diarrhea.', '["Vaccinate according to recommended schedule", "Maintain good biosecurity practices", "Control rodents and wild birds", "Practice good sanitation", "Promptly remove dead birds"]');

-- Livestock 5: Catfish (Clarias gariepinus)
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('African Catfish', 'Fish', 'The African catfish (Clarias gariepinus) is one of the most commonly farmed fish species in Nigeria. It is characterized by its scaleless body, flat head, and barbels around the mouth. It is hardy, fast-growing, and tolerant of poor water conditions.', 'Nigeria', '8-10 years (commercial cycle 6-8 months)', 'Large (1-10 kg depending on age)', 'images/livestock/catfish.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Check water quality parameters (temperature, pH, dissolved oxygen) daily", "Remove uneaten feed and dead fish", "Observe fish behavior for signs of stress or disease", "Ensure proper aeration", "Adjust feeding based on appetite and water conditions"]', 
'["Earthen ponds, concrete tanks, or plastic tanks with 1-5 fish per square meter depending on size", "Maintain water depth of 1-1.5 meters", "Ensure proper water exchange (5-10% daily)", "Provide hiding places like PVC pipes", "Install reliable aeration systems"]', 
'["Feed high-protein commercial fish feed (35-45% protein)", "Feed 3-5% of body weight daily for fingerlings, reducing to 2-3% for adults", "Divide daily ration into 2-3 feedings", "Adjust feeding based on water temperature and fish activity", "Supplement with locally available ingredients like rice bran, groundnut cake, or fish meal if necessary"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with proper management, peak in rainy season', 'Not applicable (eggs hatch in 24-48 hours after fertilization)', '10,000-50,000 eggs per kg of female body weight', 'African catfish can be bred year-round in controlled environments but naturally spawn during the rainy season. Breeding requires mature broodstock (males 1-2 years, females 1.5-2.5 years). Hormonal injection is commonly used to induce spawning. After injection, eggs are stripped from females and fertilized with milt from males. Fertilized eggs are incubated in shallow trays or tanks with gentle water flow. Hatchlings are fed with zooplankton (Artemia, Daphnia) for the first 2 weeks before transitioning to commercial feed.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Ich (White Spot Disease)', 'A parasitic disease causing white spots on skin, fins, and gills. Fish may show signs of irritation, rubbing against surfaces, and respiratory distress.', '["Maintain good water quality", "Quarantine new fish before introducing to main system", "Avoid overcrowding", "Maintain optimal water temperature (25-30°C)", "Treat with approved medications at first sign of infection"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Columnaris (Cotton Wool Disease)', 'A bacterial infection causing grayish-white patches on skin, fins, and gills that may resemble cotton. Affected areas may become reddened and ulcerated.', '["Maintain good water quality", "Avoid sudden temperature changes", "Reduce stocking density", "Ensure adequate dissolved oxygen", "Provide balanced nutrition"]');

-- Livestock 6: Tilapia
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Tilapia', 'Fish', 'Tilapia (primarily Oreochromis niloticus in Nigeria) is a popular freshwater fish known for its mild taste and adaptability. It is one of the most widely farmed fish species globally and a staple protein source in Nigeria.', 'Nigeria', '3-5 years (commercial cycle 6-8 months)', 'Medium (0.5-2 kg at harvest)', 'images/livestock/tilapia.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Check water quality parameters (temperature, pH, dissolved oxygen) daily", "Remove uneaten feed and debris", "Observe fish behavior for signs of stress or disease", "Ensure proper aeration", "Adjust feeding based on appetite and water conditions"]', 
'["Earthen ponds, concrete tanks, or cages with 3-5 fish per square meter", "Maintain water depth of 1-1.5 meters", "Ensure proper water exchange (5-10% daily)", "Provide adequate sunlight for pond productivity", "Install reliable aeration systems if stocking density is high"]', 
'["Feed commercial tilapia feed (25-35% protein)", "Feed 3-5% of body weight daily for fingerlings, reducing to 1-3% for adults", "Divide daily ration into 2-3 feedings", "Tilapia can also feed on natural pond productivity (algae, plankton)", "Supplement with locally available ingredients like rice bran or wheat bran if necessary"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round in tropical conditions', 'Females incubate eggs in mouth for 7-14 days', '200-1000 eggs per spawn, multiple spawns per year', 'Tilapia are mouth-brooders, with females incubating eggs in their mouths after fertilization. They can breed year-round in tropical conditions. For controlled breeding, maintain a ratio of 1 male to 3-4 females. In commercial settings, all-male populations are preferred to prevent unwanted reproduction and ensure faster growth. This is achieved through sex reversal (treating fry with male hormones) or using genetically improved strains. Breeding can be done in hapas (fine mesh enclosures) or dedicated breeding ponds.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Streptococcosis', 'A bacterial infection causing erratic swimming, darkening of skin, pop-eye, and high mortality. Internal symptoms include inflammation of organs.', '["Maintain good water quality", "Avoid overcrowding", "Ensure proper nutrition", "Quarantine new fish", "Vaccinate if available"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Trichodiniasis', 'A parasitic disease caused by ciliated protozoans. Symptoms include excess mucus production, respiratory distress, and skin irritation.', '["Maintain good water quality", "Avoid sudden temperature changes", "Quarantine new fish", "Reduce organic load in water", "Treat with approved medications at first sign of infection"]');

-- Livestock 7: Heterotis (African Bonytongue)
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Heterotis', 'Fish', 'Heterotis niloticus, also known as African bonytongue, is a large freshwater fish native to West Africa. It is valued for its tasty flesh and is becoming increasingly popular in aquaculture in Nigeria.', 'Nigeria', '10-15 years', 'Large (up to 100 cm and 10 kg)', 'images/livestock/heterotis.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Check water quality parameters daily", "Remove debris and maintain water plants", "Observe fish behavior for signs of stress or disease", "Ensure proper aeration", "Feed according to schedule"]', 
'["Earthen ponds with abundant vegetation", "Maintain water depth of 1-2 meters", "Stock at lower densities (1-2 fish per 10 square meters)", "Provide areas with aquatic plants for spawning", "Ensure pond has both open water and vegetated areas"]', 
'["Feed commercial fish feed (30-35% protein)", "Supplement with plant matter as heterotis are omnivorous", "Feed 2-3% of body weight daily", "Heterotis can also filter-feed on zooplankton", "Provide floating plants like duckweed as supplementary feed"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Rainy season (May-September)', 'Eggs hatch in 2-3 days', '5,000-10,000 eggs per spawn', 'Heterotis breed during the rainy season when water levels rise. They build circular nests in shallow, vegetated areas of ponds. After spawning, males guard the nest and eggs. Fry remain in the nest for about a week after hatching. For breeding, maintain mature broodstock (2-3 years old) in ponds with shallow, vegetated areas. Heterotis are monogamous, so stock equal numbers of males and females. Provide adequate vegetation for nest building.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Saprolegniasis (Fungal Infection)', 'A fungal infection causing cotton-like growths on skin, fins, and gills. Often occurs after injury or stress.', '["Maintain good water quality", "Avoid handling fish unnecessarily", "Prevent physical injuries", "Ensure proper nutrition", "Treat with approved antifungal medications when necessary"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Argulosis (Fish Lice)', 'A parasitic crustacean that attaches to the skin of fish, causing irritation, anemia, and secondary infections.', '["Maintain good water quality", "Quarantine new fish", "Regularly drain and dry ponds between cycles", "Remove aquatic vegetation where parasites can complete their life cycle", "Treat with approved medications when necessary"]');

-- Livestock 8: Muscovy Duck
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Muscovy Duck', 'Poultry', 'Muscovy ducks are large, heavy birds known for their distinctive red caruncles (fleshy growths) around the face. They are quiet, good foragers, and raised for both meat and eggs in Nigeria.', 'Nigeria (introduced)', '8-12 years', 'Large (males 4-6 kg, females 2-3 kg)', 'images/livestock/muscovy_duck.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily (deep enough for dipping head but not swimming)", "Check for signs of illness or distress", "Allow for free-range foraging if possible", "Clean feeding and drinking equipment", "Collect eggs daily"]', 
'["Provide well-ventilated housing with 0.5 sq m per duck", "Include nesting boxes for laying females", "Ensure housing is predator-proof", "Provide access to outdoor areas for foraging", "Muscovy ducks prefer to roost on perches, unlike other duck breeds"]', 
'["Feed waterfowl or poultry feed (16-20% protein)", "Allow for foraging of insects, grass, and aquatic plants", "Provide grit for proper digestion", "Ensure constant access to clean water", "Muscovy ducks require less commercial feed if allowed to forage"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with peak in rainy season', 'Eggs incubate for 33-35 days', '10-15 eggs per clutch, 2-3 clutches per year', 'Muscovy ducks can breed year-round in Nigeria, with peak breeding during the rainy season. Maintain a ratio of 1 drake (male) to 4-5 ducks for breeding. Provide secluded nesting areas with clean bedding. Muscovy females are excellent mothers and will incubate their own eggs. Ducklings require a heat source (starting at 30-32°C) for the first few weeks. Unlike other ducks, Muscovy ducks are not as water-dependent for breeding.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Duck Virus Enteritis (Duck Plague)', 'A highly contagious viral disease causing sudden deaths, nasal discharge, diarrhea, and photophobia.', '["Vaccinate according to recommended schedule", "Maintain good biosecurity practices", "Avoid contact with wild waterfowl", "Quarantine new birds", "Promptly remove dead birds"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Aspergillosis', 'A fungal infection affecting the respiratory system. Symptoms include difficulty breathing, gasping, and reduced activity.', '["Provide clean, dry bedding", "Ensure good ventilation", "Avoid moldy feed and bedding", "Reduce dust in housing", "Maintain proper humidity levels"]');

-- Livestock 9: Quail
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Japanese Quail', 'Poultry', 'Japanese quail are small game birds raised for both meat and egg production. They are valued for their rapid growth, early sexual maturity, and high egg production relative to their size.', 'Nigeria (introduced)', '2-3 years', 'Small (150-200 grams)', 'images/livestock/quail.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Collect eggs daily (often laid in early morning)", "Check for signs of illness or distress", "Ensure proper ventilation", "Clean feeding and drinking equipment"]', 
'["Provide well-ventilated housing with 0.025-0.04 sq m per bird", "Use solid flooring with bedding or wire mesh flooring", "Maintain temperature between 20-24°C for adults", "Protect from predators and extreme weather", "Provide low ceilings as quail can injure themselves by flying upward suddenly"]', 
'["Feed game bird or quail-specific feed (24-26% protein for chicks, 20-22% for adults)", "Ensure constant access to feed and water", "Provide grit for proper digestion", "Feed consumption is approximately 20-25 grams per bird daily", "Supplement with greens occasionally"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with proper management', 'Eggs incubate for 16-18 days', '250-300 eggs per year', 'Japanese quail reach sexual maturity at 6-7 weeks of age. Maintain a ratio of 1 male to 2-3 females for breeding. Quail do not typically sit on their eggs, so artificial incubation is necessary. Eggs should be collected daily and can be stored pointed end down at 13-16°C for up to 7 days before incubation. Incubate at 37.5°C with 60-65% humidity. Chicks require a heat source (starting at 35°C) for the first few weeks and higher protein feed (24-26%).');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Ulcerative Enteritis (Quail Disease)', 'A bacterial disease causing diarrhea, depression, and ulcers in the intestines. Can cause high mortality in quail.', '["Maintain good sanitation", "Avoid overcrowding", "Use preventative medications as recommended by a veterinarian", "Provide clean water and feed", "Isolate sick birds immediately"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Coccidiosis', 'A parasitic disease affecting the intestinal tract. Symptoms include bloody diarrhea, weight loss, and decreased feed consumption.', '["Use coccidiostats in feed", "Maintain dry litter", "Practice good sanitation", "Avoid overcrowding", "Clean and disinfect housing regularly"]');

-- Livestock 10: Pig (Large White)
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Large White Pig', 'Swine', 'The Large White is a popular pig breed in Nigeria known for its rapid growth, good feed conversion, and large litter size. It is characterized by its white color, erect ears, and slightly dished face.', 'Nigeria (introduced)', '5-10 years', 'Large (boars 300-350 kg, sows 250-300 kg)', 'images/livestock/large_white_pig.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Feed according to age and production stage", "Check for signs of illness or distress", "Clean feeding and drinking equipment", "Remove manure from pens", "Provide wallowing area during hot weather"]', 
'["Provide well-ventilated housing with 1.5-2 sq m per adult pig", "Use concrete flooring with good drainage", "Ensure protection from extreme temperatures", "Provide separate areas for feeding, sleeping, and defecation", "Include farrowing crates or pens for breeding sows"]', 
'["Feed balanced pig feed according to life stage (18-20% protein for piglets, 14-16% for growers, 12-14% for adults)", "Pregnant and lactating sows require higher protein and calcium", "Feed 4-5% of body weight for young pigs, reducing to 2-3% for adults", "Divide daily ration into 2-3 feedings", "Supplement with locally available ingredients like cassava, yam peels, or brewers grain if necessary"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with proper management', '114 days (3 months, 3 weeks, 3 days)', '10-14 piglets per litter, 2 litters per year', 'Large White pigs reach sexual maturity at 5-6 months but should not be bred until 8-10 months of age. Sows come into heat every 21 days. Signs of heat include restlessness, mounting behavior, and swollen vulva. Breeding can be done naturally or through artificial insemination. Provide extra nutrition during pregnancy and lactation. Prepare farrowing area with clean bedding and heat lamps for piglets. Sows can be rebred 5-7 days after weaning piglets (typically at 4-6 weeks of age).');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'African Swine Fever', 'A highly contagious viral disease with no cure or vaccine. Symptoms include high fever, loss of appetite, hemorrhages in the skin and internal organs, and high mortality.', '["Maintain strict biosecurity measures", "Control movement of pigs and pork products", "Avoid feeding uncooked food waste", "Control ticks which can transmit the virus", "Isolate new animals before introducing to the herd"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Erysipelas', 'A bacterial disease causing diamond-shaped skin lesions, fever, and arthritis. Can cause abortion in pregnant sows.', '["Vaccinate according to recommended schedule", "Maintain good hygiene", "Avoid sudden changes in temperature", "Control rodents which can carry the bacteria", "Provide clean housing and bedding"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Mange', 'A parasitic skin condition caused by mites. Symptoms include intense itching, hair loss, and thickened, crusty skin.', '["Regular inspection of skin", "Treat all animals in contact with affected pigs", "Maintain good hygiene", "Clean and disinfect housing between groups", "Use approved medications for treatment and prevention"]');

-- Livestock 11: Rabbit
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('New Zealand White Rabbit', 'Small Livestock', 'The New Zealand White is a popular rabbit breed raised for meat production in Nigeria. It is characterized by its white coat, red eyes, and good meat-to-bone ratio. It is known for its rapid growth, good feed conversion, and prolific breeding.', 'Nigeria (introduced)', '5-8 years', 'Medium (4-5 kg at maturity)', 'images/livestock/rabbit.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Feed according to age and production stage", "Check for signs of illness or distress", "Clean feeding and drinking equipment", "Remove manure from hutches", "Observe behavior and appetite"]', 
'["Provide well-ventilated hutches with 0.5-0.75 sq m per adult rabbit", "Use wire mesh flooring with solid resting areas", "Protect from extreme temperatures, drafts, and direct sunlight", "Provide nest boxes for breeding does", "Ensure protection from predators"]', 
'["Feed commercial rabbit pellets (16-18% protein)", "Provide unlimited good quality hay", "Supplement with fresh vegetables and greens", "Ensure constant access to clean water", "Pregnant and lactating does require increased feed"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with proper management', '30-32 days', '6-10 kits per litter, 4-6 litters per year', 'New Zealand White rabbits reach sexual maturity at 4-6 months of age. Does come into heat every 14-16 days and can be bred year-round. Signs of heat include restlessness and a reddish-purple vulva. Provide nest boxes with clean bedding 3-4 days before expected kindling (birth). Does will pull fur to line the nest. Kits are born hairless and blind. Does nurse kits once or twice daily. Wean kits at 4-6 weeks of age. Does can be rebred 14-21 days after kindling, though allowing more recovery time improves long-term productivity.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Coccidiosis', 'A parasitic disease affecting the liver or intestines. Symptoms include diarrhea, weight loss, poor growth, and in severe cases, death.', '["Maintain clean, dry housing", "Remove manure regularly", "Avoid overcrowding", "Use coccidiostats in feed if recommended by a veterinarian", "Provide clean water and feed"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Pasteurellosis (Snuffles)', 'A bacterial respiratory infection. Symptoms include sneezing, nasal discharge, and difficulty breathing.', '["Maintain good ventilation", "Avoid overcrowding", "Isolate affected animals", "Clean and disinfect housing regularly", "Control stress factors"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Ear Canker', 'A parasitic infection caused by mites. Symptoms include head shaking, scratching, and crusty material in the ears.', '["Regular inspection of ears", "Maintain good hygiene", "Treat all animals in contact with affected rabbits", "Use approved medications for treatment and prevention", "Clean and disinfect housing"]');

-- Livestock 12: Grasscutter (Greater Cane Rat)
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Grasscutter', 'Small Livestock', 'The grasscutter, also known as the greater cane rat, is a large rodent native to West Africa. It is raised for its meat, which is highly prized in Nigeria and other West African countries.', 'Nigeria', '4-6 years', 'Medium (4-6 kg at maturity)', 'images/livestock/grasscutter.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Feed according to age and production stage", "Check for signs of illness or distress", "Clean feeding and drinking equipment", "Remove uneaten food and feces", "Observe behavior and appetite"]', 
'["Provide concrete or wire mesh enclosures with 1-2 sq m per adult", "Include hiding places and gnawing materials", "Protect from extreme temperatures and predators", "Maintain clean, dry bedding", "Provide separate compartments for breeding females"]', 
'["Feed elephant grass, guinea grass, or other forage grasses as main diet", "Supplement with commercial rodent feed or agricultural by-products", "Provide sugarcane, cassava, or sweet potatoes as treats", "Ensure constant access to clean water", "Pregnant and lactating females require increased feed"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with peak in rainy season', '150-160 days (5 months)', '4-6 young per litter, 1-2 litters per year', 'Grasscutters reach sexual maturity at 5-6 months but should not be bred until 8-10 months of age. They are polygamous, with a recommended ratio of 1 male to 5-6 females. Females come into heat every 30-35 days. Provide nesting materials and separate compartments for pregnant females. Young are born fully furred with eyes open and can eat solid food within a few days. Wean young at 4-6 weeks of age. Allow females at least 3 months between litters for recovery.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Mange', 'A parasitic skin condition caused by mites. Symptoms include intense itching, hair loss, and thickened, crusty skin.', '["Regular inspection of skin", "Maintain good hygiene", "Isolate affected animals", "Clean and disinfect housing regularly", "Use approved medications for treatment and prevention"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Pneumonia', 'A respiratory infection causing difficulty breathing, nasal discharge, and lethargy.', '["Maintain good ventilation", "Avoid sudden temperature changes", "Provide clean, dry bedding", "Avoid overcrowding", "Isolate affected animals"]');

-- Livestock 13: Snail (Giant African Land Snail)
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Giant African Land Snail', 'Small Livestock', 'The Giant African Land Snail (Archachatina marginata) is a large snail species native to West Africa. It is raised for its meat, which is high in protein and low in fat, and is considered a delicacy in Nigeria.', 'Nigeria', '5-7 years', 'Large (shell length 10-20 cm)', 'images/livestock/snail.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water in shallow containers", "Feed in the evening as snails are nocturnal", "Mist enclosure to maintain humidity", "Remove uneaten food and feces", "Check for signs of illness or stress", "Maintain optimal temperature and humidity"]', 
'["Provide concrete pens, wooden boxes, or plastic containers with 10-15 snails per square meter", "Include 10-15 cm of loose, moist soil for burrowing and egg-laying", "Cover with wire mesh to prevent escape and predators", "Provide hiding places like broken pots or coconut shells", "Protect from direct sunlight and maintain 75-95% humidity"]', 
'["Feed leafy vegetables (lettuce, cabbage, water leaf)", "Provide fruits like pawpaw, banana, and mango", "Supplement with calcium sources like eggshells or limestone", "Feed 10-15% of body weight daily", "Ensure food is free from pesticides"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Rainy season (April-October)', 'Eggs hatch in 21-30 days', '10-20 eggs per clutch, 2-3 clutches per year', 'Giant African Land Snails are hermaphrodites, possessing both male and female reproductive organs, but they typically cross-fertilize. They reach sexual maturity at 10-12 months of age. Breeding occurs primarily during the rainy season when humidity is high. After mating, snails dig holes in moist soil and lay eggs. Provide at least 10 cm of loose, moist soil for egg-laying. Eggs hatch in 21-30 days depending on temperature and humidity. Young snails require higher protein diet and calcium for shell development.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Shell Damage', 'Physical damage to the shell that can lead to dehydration and bacterial infections.', '["Handle snails carefully", "Provide adequate calcium in diet", "Maintain proper humidity", "Remove sharp objects from enclosure", "Repair minor shell damage with calcium-rich paste"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Parasitic Infections', 'Various internal and external parasites that can affect snail health and productivity.', '["Maintain clean enclosures", "Quarantine new snails before introducing to main population", "Provide clean food and water", "Remove dead snails promptly", "Avoid overcrowding"]');

-- Livestock 14: Ostrich
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Ostrich', 'Poultry', 'The ostrich is the largest living bird species, raised for its meat, eggs, feathers, and leather. Though not native to Nigeria, ostrich farming has gained popularity due to the birds adaptability and valuable products.', 'Nigeria (introduced)', '30-40 years', 'Very Large (males 2-2.5 meters tall, 100-150 kg)', 'images/livestock/ostrich.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Provide fresh water daily", "Feed according to age and production stage", "Check for signs of illness or distress", "Clean feeding and drinking equipment", "Allow for exercise in paddocks", "Collect eggs daily during breeding season"]', 
'["Provide large paddocks with 0.25-0.5 hectares per breeding trio", "Include shelter from extreme weather", "Use strong fencing at least 2 meters high", "Provide dust bathing areas", "Ensure paddocks are free from hazards that could injure legs"]', 
'["Feed commercial ostrich feed (20-22% protein for chicks, 16-18% for growers, 14-16% for adults)", "Provide access to pasture for grazing", "Supplement with alfalfa hay or other high-quality forage", "Ensure constant access to clean water", "Provide grit for proper digestion"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Seasonal, typically during dry season (November-March)', 'Eggs incubate for 42-45 days', '40-60 eggs per season', 'Ostriches reach sexual maturity at 2-3 years of age. They are typically kept in breeding trios (1 male with 2 females) or breeding groups. The breeding season in Nigeria is typically during the dry season. Females lay eggs in a communal nest scraped in the ground by the male. Eggs should be collected daily and can be artificially incubated at 36.5°C with 25-35% humidity. Chicks require brooding at 32-35°C for the first few weeks. Provide high protein feed and avoid slippery flooring which can cause leg injuries in growing birds.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Newcastle Disease', 'A viral disease affecting respiratory and nervous systems. Symptoms include gasping, coughing, drooping wings, and twisted neck.', '["Vaccinate according to recommended schedule", "Maintain good biosecurity practices", "Control visitor access", "Quarantine new birds", "Avoid contact with wild birds"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Avian Influenza', 'A highly contagious viral disease. Symptoms include respiratory distress, decreased egg production, and high mortality.', '["Maintain strict biosecurity measures", "Avoid contact with wild birds", "Control movement of people and equipment", "Quarantine new birds", "Report suspicious deaths to authorities"]');

-- Livestock 15: Honeybee
INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
VALUES ('Honeybee', 'Insects', 'Honeybees are social insects kept for honey production, beeswax, and pollination services. Beekeeping (apiculture) is a growing industry in Nigeria, providing income and enhancing crop yields through pollination.', 'Nigeria', 'Queen: 2-5 years, Workers: 6 weeks', 'Small (1.5 cm)', 'images/livestock/honeybee.jpg');

INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
VALUES (LAST_INSERT_ID(), 
'["Check hive entrance for activity", "Observe for signs of disease or pest infestation", "Ensure access to water source", "Monitor for swarming behavior during peak season", "Minimal daily intervention is required as bees are self-sufficient"]', 
'["Provide standard Langstroth hives or Kenya Top Bar hives", "Place hives in partial shade", "Ensure hives are elevated from ground", "Position entrance away from human traffic", "Protect from strong winds and flooding"]', 
'["Bees forage for nectar and pollen naturally", "Supplement with sugar syrup (1:1 sugar to water) during dearth periods", "Provide pollen substitute if natural pollen is scarce", "Ensure clean water source within 500 meters", "Avoid feeding honey from external sources to prevent disease spread"]');

INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
VALUES (LAST_INSERT_ID(), 'Year-round with peak during flowering seasons', 'Queen cells develop in 16 days', 'Queen lays 1,500-2,000 eggs per day', 'Honeybee colonies reproduce through swarming, typically during periods of abundance. When a colony becomes crowded, workers create new queen cells. Before the new queen emerges, the old queen leaves with approximately half the workers to establish a new colony. To manage breeding, beekeepers can practice artificial swarming by splitting strong colonies. Queens can be reared by grafting young larvae into artificial queen cups. Maintain strong, healthy colonies by regularly replacing old queens (every 1-2 years), controlling pests and diseases, and ensuring adequate food stores.');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Varroa Mites', 'Parasitic mites that feed on bee hemolymph (blood), weakening bees and transmitting viruses.', '["Regular monitoring using sticky boards or sugar shake method", "Use approved miticides when infestation levels are high", "Consider resistant bee strains", "Implement integrated pest management", "Maintain strong colonies"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'American Foulbrood', 'A highly contagious bacterial disease affecting bee larvae. Infected larvae turn brown and emit a foul odor.', '["Inspect brood frames regularly", "Avoid feeding honey from unknown sources", "Replace old brood combs annually", "Maintain good apiary hygiene", "Destroy infected hives by burning in severe cases"]');

INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
VALUES (LAST_INSERT_ID(), 'Small Hive Beetle', 'A beetle that infests hives, consuming honey, pollen, and brood. Heavy infestations can cause colonies to abandon the hive.', '["Maintain strong colonies", "Keep hives in sunny locations", "Use beetle traps", "Practice good sanitation in the apiary", "Remove excess supers during dearth periods"]');
