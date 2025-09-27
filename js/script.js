document.addEventListener("DOMContentLoaded", () => {
  const searchForm = document.getElementById("plant-search-form")
  const searchInput = document.getElementById("plant-search")
  const searchResults = document.getElementById("search-results")
  const plantInfo = document.getElementById("plant-info")

  // Handle search input
  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.trim()

    if (searchTerm.length < 2) {
      searchResults.innerHTML = ""
      searchResults.classList.add("hidden")
      return
    }

    // Fetch search results
    fetch(`api/search_plants.php?term=${encodeURIComponent(searchTerm)}`)
      .then((response) => response.json())
      .then((data) => {
        searchResults.innerHTML = ""

        if (data.length === 0) {
          searchResults.innerHTML = '<div class="search-result-item">No plants found</div>'
        } else {
          data.forEach((plant) => {
            const resultItem = document.createElement("div")
            resultItem.className = "search-result-item"
            resultItem.textContent = plant.plant_name
            resultItem.addEventListener("click", () => {
              getPlantDetails(plant.plant_id)
              searchInput.value = plant.plant_name
              searchResults.innerHTML = ""
              searchResults.classList.add("hidden")
            })
            searchResults.appendChild(resultItem)
          })
        }

        searchResults.classList.remove("hidden")
      })
      .catch((error) => console.error("Error fetching search results:", error))
  })

  // Handle search form submission
  searchForm.addEventListener("submit", (e) => {
    e.preventDefault()
    const searchTerm = searchInput.value.trim()

    if (searchTerm.length > 0) {
      // Search for exact match first
      fetch(`api/search_plants.php?term=${encodeURIComponent(searchTerm)}&exact=1`)
        .then((response) => response.json())
        .then((data) => {
          if (data.length > 0) {
            getPlantDetails(data[0].plant_id)
          } else {
            // If no exact match, search for partial matches
            fetch(`api/search_plants.php?term=${encodeURIComponent(searchTerm)}`)
              .then((response) => response.json())
              .then((data) => {
                if (data.length > 0) {
                  getPlantDetails(data[0].plant_id)
                } else {
                  plantInfo.innerHTML = `
                                      <div class="no-results">
                                          <h2>No plants found</h2>
                                          <p>We couldn't find any plants matching "${searchTerm}". Please try another search term.</p>
                                      </div>
                                  `
                  plantInfo.classList.remove("hidden")
                }
              })
          }
        })
        .catch((error) => console.error("Error searching plants:", error))
    }
  })

  // Get plant details
  function getPlantDetails(plantId) {
    fetch(`api/get_plant_details.php?id=${plantId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          plantInfo.innerHTML = `<div class="error">${data.error}</div>`
        } else {
          displayPlantDetails(data)
        }
        plantInfo.classList.remove("hidden")
        // Scroll to plant info
        plantInfo.scrollIntoView({ behavior: "smooth" })
      })
      .catch((error) => console.error("Error fetching plant details:", error))
  }

  // Display plant details
  function displayPlantDetails(data) {
    const plant = data.plant
    const conditions = data.conditions
    const watering = data.watering
    const care = data.care

    // Generate difficulty class
    const difficultyClass = plant.difficulty_level.toLowerCase()

    // Create to-do list items
    const todoItems = generateTodoList(plant, conditions, watering, care)

    // Check if image_url is a URL or a local path
    let imageUrl = plant.image_url
    if (!imageUrl.startsWith("http") && !imageUrl.startsWith("https")) {
      // If it's a local path, prepend the base URL
      imageUrl = imageUrl
    }

    plantInfo.innerHTML = `
          <div class="plant-header">
              <img src="${imageUrl}" alt="${plant.plant_name}" class="plant-image" onerror="this.src='https://source.unsplash.com/featured/?${encodeURIComponent(plant.plant_name + " plant")}'">
              <div class="plant-details">
                  <h2>${plant.plant_name}</h2>
                  <p class="scientific-name">${plant.scientific_name}</p>
                  <p>${plant.description}</p>
                  <span class="difficulty ${difficultyClass}">
                      ${plant.difficulty_level} Care
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
      `
  }

  // Generate to-do list based on plant data
  function generateTodoList(plant, conditions, watering, care) {
    const currentMonth = new Date().toLocaleString("default", { month: "long" })
    const currentSeason = getSeason()

    let todoItems = ""

    // Planting tasks
    if (conditions.planting_season.includes(currentSeason)) {
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
      `Water your ${plant.plant_name} ${watering.frequency.toLowerCase()}. ${watering.special_instructions}`,
    )

    // Fertilizing tasks
    if (care.fertilizing.toLowerCase().includes(currentSeason.toLowerCase())) {
      todoItems += createTodoItem(
        "leaf",
        "Fertilize Your Plant",
        `It's time to fertilize your ${plant.plant_name}. ${care.fertilizing}`,
      )
    }

    // Pruning tasks
    if (
      care.pruning.toLowerCase().includes(currentSeason.toLowerCase()) ||
      care.pruning.toLowerCase().includes(currentMonth.toLowerCase())
    ) {
      todoItems += createTodoItem("cut", "Prune Your Plant", `${care.pruning}`)
    }

    // Pest control tasks
    todoItems += createTodoItem("bug", "Monitor for Pests", `For ${plant.plant_name}: ${care.pest_control}`)

    // Special care tasks
    todoItems += createTodoItem("heart", "Special Care", `${care.special_care}`)

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
})
