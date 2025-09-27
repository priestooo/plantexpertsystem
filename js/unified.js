document.addEventListener("DOMContentLoaded", () => {
  // Cache DOM elements for better performance
  const searchForm = document.getElementById("unified-search-form")
  const searchInput = document.getElementById("unified-search")
  const searchResults = document.getElementById("search-results")
  const plantInfo = document.getElementById("plant-info")
  const livestockInfo = document.getElementById("livestock-info")
  const infoContainer = document.getElementById("info-container")
  const categoryCards = document.querySelectorAll(".category-card")

  // Cache for storing fetched data to avoid redundant requests
  const dataCache = {
    plants: {},
    livestock: {},
    categories: {
      plants: {},
      livestock: {},
    },
  }

  // Handle search input
  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.trim()

    if (searchTerm.length < 2) {
      searchResults.innerHTML = ""
      searchResults.classList.add("hidden")
      return
    }

    // Fetch search results from unified search API
    fetch(`api/unified_search.php?term=${encodeURIComponent(searchTerm)}`)
      .then((response) => response.json())
      .then((data) => {
        searchResults.innerHTML = ""

        if (data.length === 0) {
          searchResults.innerHTML = '<div class="search-result-item">No results found</div>'
        } else {
          data.forEach((item) => {
            const resultItem = document.createElement("div")
            resultItem.className = "search-result-item"

            // Add icon based on type
            const icon = item.type === "plant" ? "seedling" : "paw"
            resultItem.innerHTML = `<i class="fas fa-${icon}"></i> ${item.name}`

            resultItem.addEventListener("click", () => {
              if (item.type === "plant") {
                // Navigate to plant details page
                window.location.href = `plant-details.php?id=${item.id}`
              } else {
                // Navigate to livestock details page
                window.location.href = `livestock-details.php?id=${item.id}`
              }
              searchInput.value = item.name
              searchResults.innerHTML = ""
              searchResults.classList.add("hidden")
            })
            searchResults.appendChild(resultItem)
          })
        }

        searchResults.classList.remove("hidden")
      })
      .catch((error) => {
        console.error("Error fetching search results:", error)
        searchResults.innerHTML = '<div class="search-result-item">Error fetching results. Please try again.</div>'
        searchResults.classList.remove("hidden")
      })
  })

  // Handle search form submission
  searchForm.addEventListener("submit", (e) => {
    e.preventDefault()
    const searchTerm = searchInput.value.trim()

    if (searchTerm.length > 0) {
      // Search for exact match first
      fetch(`api/unified_search.php?term=${encodeURIComponent(searchTerm)}&exact=1`)
        .then((response) => response.json())
        .then((data) => {
          if (data.length > 0) {
            const item = data[0]
            if (item.type === "plant") {
              // Navigate to plant details page
              window.location.href = `plant-details.php?id=${item.id}`
            } else {
              // Navigate to livestock details page
              window.location.href = `livestock-details.php?id=${item.id}`
            }
          } else {
            // If no exact match, search for partial matches
            fetch(`api/unified_search.php?term=${encodeURIComponent(searchTerm)}`)
              .then((response) => response.json())
              .then((data) => {
                if (data.length > 0) {
                  const item = data[0]
                  if (item.type === "plant") {
                    // Navigate to plant details page
                    window.location.href = `plant-details.php?id=${item.id}`
                  } else {
                    // Navigate to livestock details page
                    window.location.href = `livestock-details.php?id=${item.id}`
                  }
                } else {
                  infoContainer.innerHTML = `
                    <div class="no-results">
                      <h2>No results found</h2>
                      <p>We couldn't find any plants or livestock matching "${searchTerm}". Please try another search term.</p>
                    </div>
                  `
                  infoContainer.classList.remove("hidden")
                }
              })
          }
        })
        .catch((error) => {
          console.error("Error searching:", error)
          infoContainer.innerHTML = `
          <div class="no-results">
            <h2>Error</h2>
            <p>An error occurred while searching. Please try again.</p>
          </div>
        `
          infoContainer.classList.remove("hidden")
        })
    }
  })

  // Handle category card clicks
  categoryCards.forEach((card) => {
    card.addEventListener("click", function () {
      const category = this.getAttribute("data-category")
      const type = this.closest(".category-section").id

      // Check if category data is already cached
      if (type === "plants" && dataCache.categories.plants[category]) {
        displayCategoryResults(dataCache.categories.plants[category], category, "plant")
      } else if (type === "livestock" && dataCache.categories.livestock[category]) {
        displayCategoryResults(dataCache.categories.livestock[category], category, "livestock")
      } else {
        if (type === "plants") {
          loadPlantsByCategory(category)
        } else if (type === "livestock") {
          loadLivestockByCategory(category)
        }
      }
    })
  })

  // Display category results from cache
  function displayCategoryResults(data, category, type) {
    const resultsContainer = document.createElement("div")
    resultsContainer.className = "category-results"

    if (data.length === 0) {
      resultsContainer.innerHTML = `
        <div class="no-results">
          <h2>No ${type}s found</h2>
          <p>No ${type}s found in this category.</p>
        </div>`
    } else {
      resultsContainer.innerHTML = `<h2>${category.charAt(0).toUpperCase() + category.slice(1)}</h2>`
      const grid = document.createElement("div")
      grid.className = "results-grid"

      data.forEach((item) => {
        const card = document.createElement("div")
        card.className = "result-card"

        if (type === "plant") {
          card.innerHTML = `
            <div class="result-image">
              <img src="${item.image_url || "images/placeholder-plant.jpg"}" alt="${item.plant_name}" onerror="this.src='https://source.unsplash.com/featured/?${encodeURIComponent(item.plant_name + " plant")}'">
            </div>
            <div class="result-info">
              <h3>${item.plant_name}</h3>
              <p>${item.description ? item.description.substring(0, 100) + "..." : ""}</p>
              <a href="plant-details.php?id=${item.plant_id}" class="view-details-btn">View Details</a>
            </div>
          `
        } else {
          card.innerHTML = `
            <div class="result-image">
              <img src="${item.image_url || "images/placeholder-livestock.jpg"}" alt="${item.animal_name}" onerror="this.src='https://source.unsplash.com/featured/?${encodeURIComponent(item.animal_name)}'">
            </div>
            <div class="result-info">
              <h3>${item.animal_name}</h3>
              <p>${item.description ? item.description.substring(0, 100) + "..." : ""}</p>
              <a href="livestock-details.php?id=${item.livestock_id}" class="view-details-btn">View Details</a>
            </div>
          `
        }

        grid.appendChild(card)
      })

      resultsContainer.appendChild(grid)
    }

    infoContainer.innerHTML = ""
    infoContainer.appendChild(resultsContainer)
    infoContainer.classList.remove("hidden")
  }

  // Load plants by category with caching
  function loadPlantsByCategory(category) {
    fetch(`api/search_plants.php?category=${encodeURIComponent(category)}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        // Store in cache
        dataCache.categories.plants[category] = data
        displayCategoryResults(data, category, "plant")
      })
      .catch((error) => {
        console.error("Error loading plants:", error)
        infoContainer.innerHTML = `
          <div class="no-results">
            <h2>Error</h2>
            <p>An error occurred while loading plants. Please try again.</p>
            <button id="retry-category-btn" class="btn-primary">Retry</button>
          </div>
        `
        infoContainer.classList.remove("hidden")

        // Add retry button functionality
        document.getElementById("retry-category-btn").addEventListener("click", () => {
          loadPlantsByCategory(category)
        })
      })
  }

  // Load livestock by category with caching
  function loadLivestockByCategory(category) {
    fetch(`api/search_livestock.php?category=${encodeURIComponent(category)}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        // Store in cache
        dataCache.categories.livestock[category] = data
        displayCategoryResults(data, category, "livestock")
      })
      .catch((error) => {
        console.error("Error loading livestock:", error)
        infoContainer.innerHTML = `
          <div class="no-results">
            <h2>Error</h2>
            <p>An error occurred while loading livestock. Please try again.</p>
            <button id="retry-category-btn" class="btn-primary">Retry</button>
          </div>
        `
        infoContainer.classList.remove("hidden")

        // Add retry button functionality
        document.getElementById("retry-category-btn").addEventListener("click", () => {
          loadLivestockByCategory(category)
        })
      })
  }

  // Display plant details with optimized rendering
  function displayPlantDetails(data) {
    const plant = data.plant
    const conditions = data.conditions
    const watering = data.watering
    const care = data.care

    // Generate difficulty class
    const difficultyClass = plant.difficulty_level ? plant.difficulty_level.toLowerCase() : "moderate"

    // Create to-do list items
    const todoItems = generateTodoList(plant, conditions, watering, care)

    // Check if image_url is a URL or a local path
    let imageUrl = plant.image_url
    if (!imageUrl || (!imageUrl.startsWith("http") && !imageUrl.startsWith("https"))) {
      // If it's a local path or empty, use a placeholder
      imageUrl = imageUrl || "images/placeholder-plant.jpg"
    }

    // Create the HTML content
    const content = `
      <div class="plant-header">
        <img src="${imageUrl}" alt="${plant.plant_name}" class="plant-image" onerror="this.src='https://source.unsplash.com/featured/?${encodeURIComponent(plant.plant_name + " plant")}'">
        <div class="plant-details">
          <h2>${plant.plant_name}</h2>
          <p class="scientific-name">${plant.scientific_name}</p>
          <p>${plant.description}</p>
          <span class="difficulty ${difficultyClass}">
            ${plant.difficulty_level || "Moderate"} Care
          </span>
        </div>
      </div>
      
      <div class="care-sections">
        <div class="care-section">
          <h3><i class="fas fa-seedling"></i> Planting Conditions</h3>
          <ul>
            <li><strong>Soil Type:</strong> ${conditions.soil_type}</li>
            <li><strong>Light:</strong> ${conditions.light_requirements}</li>
            <li><strong>Temperature:</strong> ${conditions.temperature_range}</li>
            <li><strong>Humidity:</strong> ${conditions.humidity_level}</li>
            <li><strong>Planting Season:</strong> ${conditions.planting_season}</li>
            <li><strong>Planting Depth:</strong> ${conditions.planting_depth}</li>
            <li><strong>Spacing:</strong> ${conditions.spacing}</li>
          </ul>
        </div>
        
        <div class="care-section">
          <h3><i class="fas fa-tint"></i> Watering Schedule</h3>
          <ul>
            <li><strong>Frequency:</strong> ${watering.frequency}</li>
            <li><strong>Amount:</strong> ${watering.amount}</li>
            <li><strong>Special Instructions:</strong> ${watering.special_instructions}</li>
          </ul>
        </div>
        
        <div class="care-section">
          <h3><i class="fas fa-hand-holding-heart"></i> Care Instructions</h3>
          <ul>
            <li><strong>Fertilizing:</strong> ${care.fertilizing}</li>
            <li><strong>Pruning:</strong> ${care.pruning}</li>
            <li><strong>Pest Control:</strong> ${care.pest_control}</li>
            <li><strong>Disease Prevention:</strong> ${care.disease_prevention}</li>
            <li><strong>Special Care:</strong> ${care.special_care}</li>
          </ul>
        </div>
      </div>
      
      <div class="todo-list">
        <h3>Your Customized To-Do List for ${plant.plant_name}</h3>
        <div class="todo-items">
          ${todoItems}
        </div>
      </div>
      
      <div class="farm-tools-banner">
        <h3>Do you need farm tools or workers for your farm?</h3>
        <p>Agros has got you covered with quality tools and reliable workers.</p>
        <a href="" class="farm-tools-btn" target="_blank">Click here now</a>
      </div>
      
      <button id="back-button" class="btn-primary">Back to Results</button>
    `

    // Update the DOM in a single operation
    plantInfo.innerHTML = content

    // Add event listener to back button
    document.getElementById("back-button").addEventListener("click", () => {
      plantInfo.classList.add("hidden")
      if (document.querySelector(".category-results")) {
        document.querySelector(".category-results").style.display = "block"
      } else {
        // If there are no category results, just hide the plant info
        infoContainer.classList.add("hidden")
      }
    })

    // Show plant info and hide livestock info
    plantInfo.classList.remove("hidden")
    livestockInfo.classList.add("hidden")
    infoContainer.classList.remove("hidden")
  }

  // Display livestock details with optimized rendering
  function displayLivestockDetails(data) {
    const animal = data.animal
    const care = data.care
    const breeding = data.breeding
    const diseases = data.diseases

    // Check if image_url is a URL or a local path
    let imageUrl = animal.image_url
    if (!imageUrl || (!imageUrl.startsWith("http") && !imageUrl.startsWith("https"))) {
      // If it's a local path or empty, use a placeholder
      imageUrl = imageUrl || "images/placeholder-livestock.jpg"
    }

    // Get WhatsApp number from animal data
    const whatsappNumber = animal.vet_whatsapp || "2348012345678"

    // Create the HTML content
    const content = `
      <div class="livestock-details">
        <div class="livestock-details-header">
          <div class="livestock-details-image">
            <img src="${imageUrl}" alt="${animal.animal_name}" onerror="this.src='https://source.unsplash.com/featured/?${encodeURIComponent(animal.animal_name)}'">
          </div>
          <div class="livestock-details-info">
            <h2>${animal.animal_name}</h2>
            <span class="livestock-details-category">${animal.category}</span>
            <p class="livestock-details-description">${animal.description}</p>
            <div class="livestock-details-meta">
              <div class="meta-item"><strong>Origin:</strong> ${animal.origin}</div>
              <div class="meta-item"><strong>Lifespan:</strong> ${animal.lifespan}</div>
              <div class="meta-item"><strong>Size:</strong> ${animal.size}</div>
            </div>
          </div>
        </div>
        
        <div class="livestock-details-tabs">
          <div class="tabs-nav">
            <button class="tab-button active" data-tab="care">Care Guide</button>
            <button class="tab-button" data-tab="feeding">Feeding</button>
            <button class="tab-button" data-tab="breeding">Breeding</button>
            <button class="tab-button" data-tab="health">Health & Diseases</button>
          </div>
          
          <div id="care-tab" class="tab-content active">
            <div class="care-steps">
              <h4>Daily Care Routine</h4>
              <ul class="step-list">
                ${care.daily_routine.map((step) => `<li>${step}</li>`).join("")}
              </ul>
            </div>
            
            <div class="care-steps">
              <h4>Housing Requirements</h4>
              <ul class="step-list">
                ${care.housing.map((step) => `<li>${step}</li>`).join("")}
              </ul>
            </div>
          </div>
          
          <div id="feeding-tab" class="tab-content">
            <div class="feeding-steps">
              <h4>Feeding Guide</h4>
              <ul class="step-list">
                ${care.feeding.map((step) => `<li>${step}</li>`).join("")}
              </ul>
            </div>
          </div>
          
          <div id="breeding-tab" class="tab-content">
            <div class="breeding-info">
              <h4>Breeding Information</h4>
              <div class="breeding-meta">
                <div class="breeding-meta-item"><strong>Breeding Season:</strong> ${breeding.season}</div>
                <div class="breeding-meta-item"><strong>Gestation Period:</strong> ${breeding.gestation_period}</div>
                <div class="breeding-meta-item"><strong>Litter Size:</strong> ${breeding.litter_size}</div>
              </div>
              <p>${breeding.description}</p>
            </div>
          </div>
          
          <div id="health-tab" class="tab-content">
            <div class="diseases-list">
              ${diseases
                .map(
                  (disease) => `
                <div class="disease-item">
                  <h4>${disease.name}</h4>
                  <p>${disease.description}</p>
                  <div class="prevention-tips">
                    <h5>Prevention Tips</h5>
                    <ul>
                      ${disease.prevention.map((tip) => `<li>${tip}</li>`).join("")}
                    </ul>
                  </div>
                </div>
              `,
                )
                .join("")}
            </div>
            
            <div class="vet-contact">
              <div class="vet-contact-icon">
                <i class="fab fa-whatsapp"></i>
              </div>
              <div class="vet-contact-info">
                <h4>Need Veterinary Assistance?</h4>
                <p>Contact our expert veterinarians for immediate help with any health concerns.</p>
                <a href="https://wa.me/${whatsappNumber}" class="whatsapp-button" target="_blank">
                  <i class="fab fa-whatsapp"></i> Contact Vet on WhatsApp
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <button id="livestock-back-button" class="btn-primary">Back to Results</button>
    `

    // Update the DOM in a single operation
    livestockInfo.innerHTML = content

    // Add event listeners for tabs
    const tabButtons = document.querySelectorAll(".tab-button")
    const tabContents = document.querySelectorAll(".tab-content")

    tabButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const tabId = button.getAttribute("data-tab")

        // Update active tab button
        tabButtons.forEach((btn) => btn.classList.remove("active"))
        button.classList.add("active")

        // Update active tab content
        tabContents.forEach((content) => content.classList.remove("active"))
        document.getElementById(`${tabId}-tab`).classList.add("active")
      })
    })

    // Add event listener for back button
    document.getElementById("livestock-back-button").addEventListener("click", () => {
      livestockInfo.classList.add("hidden")
      if (document.querySelector(".category-results")) {
        document.querySelector(".category-results").style.display = "block"
      } else {
        // If there are no category results, just hide the livestock info
        infoContainer.classList.add("hidden")
      }
    })

    // Show livestock info and hide plant info
    livestockInfo.classList.remove("hidden")
    plantInfo.classList.add("hidden")
    infoContainer.classList.remove("hidden")
  }

  // Generate to-do list based on plant data
  function generateTodoList(plant, conditions, watering, care) {
    const currentMonth = new Date().toLocaleString("default", { month: "long" })
    const currentSeason = getSeason()

    let todoItems = ""

    // Planting tasks
    if (conditions.planting_season && conditions.planting_season.includes(currentSeason)) {
      todoItems += createTodoItem(
        "seedling",
        "Plant Your " + plant.plant_name,
        `Now is a great time to plant your ${plant.plant_name}! Use ${conditions.soil_type} and plant at a depth of ${conditions.planting_depth}.`,
      )
    }

    // Watering tasks
    todoItems += createTodoItem(
      "tint",
      "Watering Schedule",
      `Water your ${plant.plant_name} ${watering.frequency ? watering.frequency.toLowerCase() : "regularly"}. ${watering.special_instructions || ""}`,
    )

    // Fertilizing tasks
    if (care.fertilizing && care.fertilizing.toLowerCase().includes(currentSeason.toLowerCase())) {
      todoItems += createTodoItem(
        "leaf",
        "Fertilize Your Plant",
        `It's time to fertilize your ${plant.plant_name}. ${care.fertilizing}`,
      )
    }

    // Pruning tasks
    if (
      care.pruning &&
      (care.pruning.toLowerCase().includes(currentSeason.toLowerCase()) ||
        care.pruning.toLowerCase().includes(currentMonth.toLowerCase()))
    ) {
      todoItems += createTodoItem("cut", "Prune Your Plant", `${care.pruning}`)
    }

    // Pest control tasks
    todoItems += createTodoItem(
      "bug",
      "Monitor for Pests",
      `For ${plant.plant_name}: ${care.pest_control || "Regularly check for pests and treat as needed."}`,
    )

    // Special care tasks
    todoItems += createTodoItem(
      "heart",
      "Special Care",
      `${care.special_care || "Give your plant extra attention during extreme weather conditions."}`,
    )

    return todoItems
  }

  // Create a to-do item HTML
  function createTodoItem(icon, title, description) {
    return `
      <div class="todo-item">
        <div class="todo-icon">
          <i class="fas fa-${icon}"></i>
        </div>
        <div class="todo-content">
          <h4>${title}</h4>
          <p>${description}</p>
        </div>
      </div>
    `
  }

  // Get current season
  function getSeason() {
    const month = new Date().getMonth()

    if (month >= 2 && month <= 4) return "Spring"
    if (month >= 5 && month <= 7) return "Summer"
    if (month >= 8 && month <= 10) return "Fall"
    return "Winter"
  }

  // Preload common categories to improve perceived performance
  function preloadCommonCategories() {
    // Preload common plant categories
    fetch("api/search_plants.php?category=vegetables")
      .then((response) => response.json())
      .then((data) => {
        dataCache.categories.plants["vegetables"] = data
      })
      .catch((error) => console.error("Error preloading vegetables:", error))

    // Preload common livestock categories
    fetch("api/search_livestock.php?category=poultry")
      .then((response) => response.json())
      .then((data) => {
        dataCache.categories.livestock["poultry"] = data
      })
      .catch((error) => console.error("Error preloading poultry:", error))
  }

  // Initialize preloading after a short delay to prioritize initial page load
  setTimeout(preloadCommonCategories, 2000)

  // Handle URL parameters for category filtering
  function handleUrlParameters() {
    const urlParams = new URLSearchParams(window.location.search)
    const plantCategory = urlParams.get("plant_category")
    const livestockCategory = urlParams.get("livestock_category")

    if (plantCategory) {
      // Find the plant category card and trigger a click
      const categoryCard = document.querySelector(`.category-card[data-category="${plantCategory}"]`)
      if (categoryCard) {
        categoryCard.click()
        // Scroll to the plants section
        document.getElementById("plants").scrollIntoView({ behavior: "smooth" })
      }
    } else if (livestockCategory) {
      // Find the livestock category card and trigger a click
      const categoryCard = document.querySelector(`.category-card[data-category="${livestockCategory}"]`)
      if (categoryCard) {
        categoryCard.click()
        // Scroll to the livestock section
        document.getElementById("livestock").scrollIntoView({ behavior: "smooth" })
      }
    }
  }

  // Call this function when the page loads
  handleUrlParameters()
})

