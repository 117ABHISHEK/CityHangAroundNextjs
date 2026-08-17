
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
      /* .category-chip {
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 14px;
        color: #333;
        background-color: #fff;
        cursor: pointer;
        transition: background-color 0.2s, box-shadow 0.2s;
      }
      .category-chip:hover {
        background-color: #e4e4e4;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.15);
      } */
      .sidebar-link {
        display: block;
        padding: 8px 12px;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
      }
      .sidebar-link:hover {
        background-color: #f1f5ff;
        color: #0d6efd;
      }
      .sidebar-link.active {
        background-color: #e7f0ff;
        font-weight: 600;
        color: #0d6efd;
      }
      .group-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        transition: box-shadow 0.3s;
        background-color: #fff;
        text-align: center;
        padding: 16px;
      }
      .group-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
      .group-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 6px;
        background-color: #eee;
      }
      .like-btn {
        margin-left: 10px;
      }
      .like-btn.liked {
        color: #e91e63;
        font-weight: bold;
        border-color: #e91e63;
      }
      #categoryList {
        max-height: 50vh;
        overflow-y: auto;

        /* Hide scrollbar for Chrome, Safari, Edge */
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
      }
      #categoryList::-webkit-scrollbar {
        display: none; /* Chrome, Safari */
      }
      .custom-btn {
        background-color: #ff4939 !important;
        border-color: #ff4939 !important;
        color: #fff !important;
      }
      .custom-btn:hover {
        background-color: #e03d2f !important;
        border-color: #e03d2f !important;
      }

      /* Outline button */
      .custom-outline-btn {
        background-color: transparent !important;
        border: 2px solid #ff4939 !important;
        color: #ff4939 !important;
      }
      .custom-outline-btn:hover {
        background-color: #ff4939 !important;
        color: #fff !important;
      }
      /* Card hover effect */

      .group-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
      }
      .group-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      }
      .group-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
      }
    </style>
  </head>

  <body>
    <div class="container-fluid mt-4">
      <div class="row">
        <!-- left sidebar -->
        <div class="col-md-3">
          <div
            class="sidebar-section sticky-top bg-white shadow-sm p-3 rounded-4 border"
          >
            <!-- Accordion for Dropdowns -->
            <div class="accordion mb-4" id="sidebarAccordion">
              <!-- Trending Topics -->
              <div class="accordion-item border-0">
                <h2 class="accordion-header" id="trendingHeading">
                  <button
                    class="accordion-button collapsed shadow-sm rounded-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#trendingCollapse"
                    aria-expanded="false"
                    aria-controls="trendingCollapse"
                  >
                    🔥 Trending Topics
                  </button>
                </h2>
                <div
                  id="trendingCollapse"
                  class="accordion-collapse collapse"
                  aria-labelledby="trendingHeading"
                >
                  <div class="accordion-body">
                    <ul class="list-unstyled mb-0">
                      <li><a href="#" class="sidebar-link">AI & Tech</a></li>
                      <li><a href="#" class="sidebar-link">Startups</a></li>
                      <li><a href="#" class="sidebar-link">Sports</a></li>
                      <li><a href="#" class="sidebar-link">Movies</a></li>
                      <li><a href="#" class="sidebar-link">Travel</a></li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Categories -->
              <div class="accordion-item border-0">
                <h2 class="accordion-header" id="categoryHeading">
                  <button
                    class="accordion-button collapsed shadow-sm rounded-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#categoryCollapse"
                    aria-expanded="false"
                    aria-controls="categoryCollapse"
                  >
                    📚 Categories
                  </button>
                </h2>
                <div
                  id="categoryCollapse"
                  class="accordion-collapse collapse"
                  aria-labelledby="categoryHeading"
                >
                  <div class="accordion-body">
                    <ul class="list-unstyled mb-0">
                      <li>
                        <a href="/category/social" class="sidebar-link"
                          >Social</a
                        >
                      </li>
                      <li>
                        <a href="/category/games" class="sidebar-link">Games</a>
                      </li>
                      <li>
                        <a href="/category/education" class="sidebar-link"
                          >Education</a
                        >
                      </li>
                      <li>
                        <a href="/category/health" class="sidebar-link"
                          >Health</a
                        >
                      </li>
                      <li>
                        <a href="/category/fitness" class="sidebar-link"
                          >Fitness</a
                        >
                      </li>
                      <li>
                        <a href="/category/politics" class="sidebar-link"
                          >Politics</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Filters -->
              <div class="accordion-item border-0">
                <h2 class="accordion-header" id="filterHeading">
                  <button
                    class="accordion-button collapsed shadow-sm rounded-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#filterCollapse"
                    aria-expanded="false"
                    aria-controls="filterCollapse"
                  >
                    🔍 Filters
                  </button>
                </h2>
                <div
                  id="filterCollapse"
                  class="accordion-collapse collapse"
                  aria-labelledby="filterHeading"
                >
                  <div class="accordion-body">
                    <div class="form-check mb-2">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="popular"
                      />
                      <label class="form-check-label" for="popular"
                        >Most Popular</label
                      >
                    </div>
                    <div class="form-check mb-2">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="newest"
                      />
                      <label class="form-check-label" for="newest"
                        >Newest</label
                      >
                    </div>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="active"
                      />
                      <label class="form-check-label" for="active"
                        >Active Now</label
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2">
              <button class="btn custom-btn rounded-3">➕ Create Group</button>
              <button class="btn custom-outline-btn rounded-3">
                👥 My Groups
              </button>
            </div>
          </div>
        </div>

        <!-- Main Content: Group Cards -->
        <div class="col-md-6">
          <div class="row">
            <!-- Group 1 -->
            <!-- <div class="col-md-6">
              <div class="group-card">
                <img
                  src="https://miro.medium.com/v2/resize:fit:1400/1*M-b093jQIpmapIIaxH7N7g.jpeg"
                  alt="comic life"
                />
                <h5 class="mt-2">comic life</h5>
                <p class="text-muted mb-1">Social Group</p>
                <p class="small text-secondary">5 Members</p>
                <button class="btn btn-danger">Join</button>
                <button
                  class="btn btn-outline-danger like-btn"
                  onclick="toggleLike(this)"
                >
                  ❤️ Like
                </button>
              </div>
            </div> -->
            <div class="col-md-6">
              <div
                class="group-card card border-0 shadow-sm rounded-4 overflow-hidden h-100"
              >
                <!-- Image with overlay -->
                <div class="position-relative">
                  <img
                    src="https://miro.medium.com/v2/resize:fit:1400/1*M-b093jQIpmapIIaxH7N7g.jpeg"
                    class="card-img-top"
                    alt="comic life"
                  />
                  <!-- <span
                    class="badge bg-danger position-absolute top-0 end-0 m-2 px-3 py-2 rounded-pill"
                  >
                    Social
                  </span> -->
                </div>

                <!-- Card Body -->
                <div class="card-body">
                  <h5 class="card-title fw-bold mb-1">Comic Life</h5>
                  <p class="text-muted mb-2">Social Group</p>
                  <p class="small text-secondary mb-3">👥 5 Members</p>

                  <!-- Action Buttons -->
                  <div class="d-flex gap-2">
                    <button class="btn custom-btn flex-fill rounded-3">
                      🚀 Join
                    </button>
                    <button
                      class="btn custom-outline-btn flex-fill rounded-3 like-btn"
                      onclick="toggleLike(this)"
                    >
                      ❤️ Like
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Group 2 -->
            <!-- Group 2 -->
            <div class="col-md-6">
              <div
                class="group-card shadow-sm border rounded-4 overflow-hidden h-100"
              >
                <img
                  src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmNHLC5OThjRU_GI-DsRzOMJaKu9gMJXa15w&s"
                  alt="Battle Arena"
                  class="img-fluid group-img"
                />
                <div class="p-3">
                  <h5 class="fw-bold text-dark">Battle Arena Group</h5>
                  <p class="text-muted small mb-1">Game</p>
                  <p class="text-secondary small">👥 4 Members</p>
                  <div class="d-flex gap-2">
                    <button class="btn custom-btn flex-fill rounded-3">
                      🚀 Join
                    </button>
                    <button
                      class="btn custom-outline-btn flex-fill rounded-3 like-btn"
                      onclick="toggleLike(this)"
                    >
                      ❤️ Like
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Group 3 -->
            <div class="col-md-6">
              <div
                class="group-card shadow-sm border rounded-4 overflow-hidden h-100"
              >
                <img
                  src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXemrMQqJB33pN9jzioaoyQlrEejtqaH3oYQ&s"
                  alt="Keep Calm"
                  class="img-fluid group-img"
                />
                <div class="p-3">
                  <h5 class="fw-bold text-dark">Keep Calm</h5>
                  <p class="text-muted small mb-1">Education & Learning</p>
                  <p class="text-secondary small">👥 1 Member</p>
                  <div class="d-flex gap-2">
                    <button class="btn custom-btn flex-fill rounded-3">
                      🚀 Join
                    </button>
                    <button
                      class="btn custom-outline-btn flex-fill rounded-3 like-btn"
                      onclick="toggleLike(this)"
                    >
                      ❤️ Like
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Sidebar (Optional) -->
        <div class="col-md-3">
          <aside style="margin-top: 0">
            <!-- 🎯 Sponsors Section -->
            <div
              style="
                margin-bottom: 24px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 16px;
              "
            >
              <h3
                style="
                  font-size: 16px;
                  font-weight: 600;
                  margin-bottom: 16px;
                  border-bottom: 2px solid #eee;
                  padding-bottom: 8px;
                "
              >
                🎯 Sponsored
              </h3>
              <div style="display: flex; flex-direction: column; gap: 12px">
                <!-- Sponsor 1 -->
                <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    padding: 10px;
                    border-radius: 6px;
                  "
                >
                  <img
                    src="https://1000logos.net/wp-content/uploads/2017/03/Nike-Logo-500x281.png"
                    alt="Nike"
                    style="margin-right: 10px; border-radius: 4px"
                    width="50"
                    height="50"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-weight: 600; font-size: 14px"
                    >
                      Nike
                    </h6>
                    <small style="color: #666">Performance gear & offers</small>
                  </div>
                </a>

                <!-- Sponsor 2 -->
                <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    padding: 10px;
                    border-radius: 6px;
                  "
                >
                  <img
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Spotify_icon.svg/512px-Spotify_icon.svg.png"
                    alt="Spotify"
                    style="margin-right: 10px; border-radius: 4px"
                    width="50"
                    height="50"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-weight: 600; font-size: 14px"
                    >
                      Spotify
                    </h6>
                    <small style="color: #666">Stream music free</small>
                  </div>
                </a>
              </div>
            </div>

            <!-- 🔥 Trending Products Section -->
            <div
              style="
                margin-bottom: 24px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 16px;
              "
            >
              <h3
                style="
                  font-size: 16px;
                  font-weight: 600;
                  margin-bottom: 16px;
                  border-bottom: 2px solid #eee;
                  padding-bottom: 8px;
                "
              >
                🔥 Trending Products
              </h3>
              <div style="display: flex; flex-direction: column; gap: 12px">
                <!-- Product 1 -->
                <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    border-radius: 6px;
                    padding: 8px;
                  "
                >
                  <img
                    src="https://rukminim2.flixcart.com/image/416/416/xif0q/diary-notebook/2/y/6/a5-spiral-diary-lovelydesign-original-imagnggfw9vwpmqz.jpeg"
                    alt="Diary"
                    width="50"
                    height="50"
                    style="margin-right: 10px; border-radius: 4px"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-size: 14px; font-weight: 600"
                    >
                      Handmade Diary
                    </h6>
                    <small style="color: #777">₹150 - Stationery</small>
                  </div>
                </a>

                <!-- Product 2 -->
                <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    border-radius: 6px;
                    padding: 8px;
                  "
                >
                  <img
                    src="https://rukminim2.flixcart.com/image/416/416/kawtvgw0/speaker/mobile-tablet-speaker/y/e/s/portable-bluetooth-speaker-wireless-speaker-original-imafs9cnhhrsyqek.jpeg"
                    alt="Speaker"
                    width="50"
                    height="50"
                    style="margin-right: 10px; border-radius: 4px"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-size: 14px; font-weight: 600"
                    >
                      Bluetooth Speaker
                    </h6>
                    <small style="color: #777">₹899 - Electronics</small>
                  </div>
                </a>

                <!-- Product 3 -->
                <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    border-radius: 6px;
                    padding: 8px;
                  "
                >
                  <img
                    src="https://rukminim2.flixcart.com/image/416/416/xif0q/scarf/h/h/s/free-na-muffler2combo-jai-textiles-original-imagkk7umudzyybt.jpeg"
                    alt="Muffler"
                    width="50"
                    height="50"
                    style="margin-right: 10px; border-radius: 4px"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-size: 14px; font-weight: 600"
                    >
                      Woolen Muffler
                    </h6>
                    <small style="color: #777">₹299 - Apparel</small>
                  </div>
                </a>
              </div>
            </div>

            <!-- 📌 Featured Pages Section -->
            <div
              style="
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 16px;
              "
            >
              <h3
                style="
                  font-size: 16px;
                  font-weight: 600;
                  margin-bottom: 16px;
                  border-bottom: 2px solid #eee;
                  padding-bottom: 8px;
                "
              >
                📌 Featured Pages
              </h3>
              <div style="display: flex; flex-direction: column; gap: 12px">
                <a
                  href="#"
                  style="
                    text-decoration: none;
                    color: #000;
                    background: #fafafa;
                    padding: 8px;
                    border-radius: 6px;
                    border: 1px solid #eee;
                  "
                >
                  <h6 style="margin: 0; font-size: 14px">Eco-friendly Bags</h6>
                  <small style="color: #777"
                    >Top seller in handmade items</small
                  >
                </a>
                <a
                  href="#"
                  style="
                    text-decoration: none;
                    color: #000;
                    background: #fafafa;
                    padding: 8px;
                    border-radius: 6px;
                    border: 1px solid #eee;
                  "
                >
                  <h6 style="margin: 0; font-size: 14px">Organic Honey</h6>
                  <small style="color: #777"
                    >Top seller in natural products</small
                  >
                </a>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>

    <script>
      function toggleLike(button) {
        button.classList.toggle("liked");
        button.innerHTML = button.classList.contains("liked")
          ? "💖 Liked"
          : "❤️ Like";
      }

      function filterTopics(query) {
        const chips = document.querySelectorAll("#categoryList .category-chip");
        query = query.toLowerCase();
        chips.forEach((chip) => {
          const text = chip.textContent.toLowerCase();
          chip.style.display = text.includes(query) ? "inline-block" : "none";
        });
      }
    </script>

