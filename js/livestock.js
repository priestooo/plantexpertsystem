document.addEventListener("DOMContentLoaded", () => {
  const searchForm = document.getElementById("livestock-search-form")
  const searchInput = document.getElementById("livestock-search")
  const searchButton = document.getElementById("search-button")
  const searchResults = document.getElementById("search-results")
  const livestockGrid = document.getElementById("livestock-grid")
  const modal = document.getElementById("livestock-modal")
  const closeButton = document.querySelector(".close-button")
  const detailsContainer = document.getElementById("livestock-details-container")
  const livestockInfo = document.getElementById("livestock-info")
  const categoryButtons = document.querySelectorAll(".category-btn")
  const categoryFilter = document.getElementById("category-filter")

  // Load all livestock on page load
  loadLivestock("all")

  // Handle category filter
  categoryButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const category = this.getAttribute("data-category")

      // Update active button
      categoryButtons.forEach((btn) => btn.classList.remove("active"))
      this.classList.add("active")

      // Load livestock by category
      loadLivestock(category)

      // Hide livestock info if showing
      livestockInfo.classList.add("hidden")
    })
  })

  // Handle search input
  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.trim()

    if (searchTerm.length < 2) {
      searchResults.innerHTML = ""
      searchResults.classList.add("hidden")
      return
    }

    // Fetch search results
    fetch(`api/search_livestock.php?term=${encodeURIComponent(searchTerm)}`)
      .then((response) => response.json())
      .then((data) => {
        searchResults.innerHTML = ""

        if (data.length === 0) {
          searchResults.innerHTML = '<div class="search-result-item">No livestock found</div>'
        } else {
          data.forEach((animal) => {
            const resultItem = document.createElement("div")
            resultItem.className = "search-result-item"
            resultItem.textContent = animal.animal_name
            resultItem.addEventListener("click", () => {
              getLivestockDetails(animal.livestock_id)
              searchInput.value = animal.animal_name
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
      fetch(`api/search_livestock.php?term=${encodeURIComponent(searchTerm)}&exact=1`)
        .then((response) => response.json())
        .then((data) => {
          if (data.length > 0) {
            getLivestockDetails(data[0].livestock_id)
          } else {
            // If no exact match, search for partial matches
            fetch(`api/search_livestock.php?term=${encodeURIComponent(searchTerm)}`)
              .then((response) => response.json())
              .then((data) => {
                if (data.length > 0) {
                  getLivestockDetails(data[0].livestock_id)
                } else {
                  livestockInfo.innerHTML = `
                    <div class="no-results">
                      <h2>No livestock found</h2>
                      <p>We couldn't find any livestock matching "${searchTerm}". Please try another search term.</p>
                    </div>
                  `
                  livestockInfo.classList.remove("hidden")
                  livestockGrid.classList.add("hidden")
                }
              })
          }
        })
        .catch((error) => console.error("Error searching livestock:", error))
    }
  })

  // Load livestock by category
  function loadLivestock(category) {
    fetch(`api/search_livestock.php?category=${category}`)
      .then((response) => response.json())
      .then((data) => {
        livestockGrid.innerHTML = ""

        if (data.length === 0) {
          livestockGrid.innerHTML =
            '<div class="no-results"><h2>No livestock found</h2><p>No livestock found in this category.</p></div>'
        } else {
          data.forEach((animal) => {
            const card = createLivestockCard(animal)
            livestockGrid.appendChild(card)
          })
        }

        livestockGrid.classList.remove("hidden")
      })
      .catch((error) => console.error("Error loading livestock:", error))
  }

  // Create livestock card
  function createLivestockCard(animal) {
    const card = document.createElement("div")
    card.className = "livestock-card"
    card.addEventListener("click", () => getLivestockDetails(animal.livestock_id))

    // Check if image_url is a URL or a local path
    let imageUrl = animal.image_url
    if (!imageUrl.startsWith("http") && !imageUrl.startsWith("https")) {
      // If it's a local path, prepend the base URL
      imageUrl = imageUrl
    }

    card.innerHTML = `
      <img src="${imageUrl}" alt="${animal.animal_name}" class="livestock-image" onerror="this.src='https://source.unsplash.com/featured/?${encodeURIComponent(animal.animal_name)}'">
      <div class="livestock-details">
        <h3>${animal.animal_name}</h3>
        <span class="livestock-category">${animal.category}</span>
        <p class="livestock-description">${animal.description.substring(0, 100)}...</p>
        <button class="view-details-btn">View Details</button>
      </div>
    `

    return card
  }

  // Get livestock details
  function getLivestockDetails(livestockId) {
    fetch(`api/get_livestock_details.php?id=${livestockId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          livestockInfo.innerHTML = `<div class="error">${data.error}</div>`
        } else {
          displayLivestockDetails(data)
        }

        livestockInfo.classList.remove("hidden")
        livestockGrid.classList.add("hidden")
        categoryFilter.classList.add("hidden")

        // Scroll to livestock info
        livestockInfo.scrollIntoView({ behavior: "smooth" })
      })
      .catch((error) => console.error("Error fetching livestock details:", error))
  }

  // Display livestock details
  function displayLivestockDetails(data) {
    const animal = data.animal
    const care = data.care
    const breeding = data.breeding
    const diseases = data.diseases

    // Check if image_url is a URL or a local path
    let imageUrl = animal.image_url
    if (!imageUrl.startsWith("http") && !imageUrl.startsWith("https")) {
      // If it's a local path, prepend the base URL
      imageUrl = imageUrl
    }

    livestockInfo.innerHTML = `
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
            <button class="btn-primary" id="back-to-list">Back to List</button>
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v4h4v2h-4v4h-2v-4H7v-2h4z"/></svg>
              </div>
              <div class="vet-contact-info">
                <h4>Need Veterinary Assistance?</h4>
                <p>Contact our expert veterinarians for immediate help with any health concerns.</p>
                <a href="#" class="whatsapp-button">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v4h4v2h-4v4h-2v-4H7v-2h4z"/></svg>
                  Contact Vet
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    `

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
    document.getElementById("back-to-list").addEventListener("click", () => {
      livestockInfo.classList.add("hidden")
      livestockGrid.classList.remove("hidden")
      categoryFilter.classList.remove("hidden")
    })
  }
})

