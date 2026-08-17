<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Blogs</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet" media="print" onload="this.media='all'" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
        <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    </noscript>

    <style>
      body {
        background-color: #f8f9fa;
        font-family: "Segoe UI", sans-serif;
      }
      .blog-wrapper {
        max-width: 1450px;
        margin: 20px;
      }
      .blog-banner {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
      }
      .blog-title {
        font-size: 1.8rem;
        font-weight: 700;
      }
      .author-box {
        display: flex;
        align-items: center;
        margin-top: 10px;
      }
      .author-box img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
      }
      .sidebar-widget {
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
      }
      .review-box,
      .comments-box {
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        margin-top: 30px;
      }
      .review-stars {
        color: #ffc107;
        font-size: 20px;
      }
      .comment-item {
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 10px;
      }
      .comment-item:last-child {
        border-bottom: none;
      }
      /* Views & Comments info */
      .post-stats {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
      }
      .post-stats span {
        margin-right: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
      }
      .post-stats svg {
        fill: #666;
        width: 18px;
        height: 18px;
      }
      .bg-logo {
        background-color: #ff4939;
      }
    </style>
  </head>
  <body>
    <div class="container blog-wrapper">
      <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 order-1 order-lg-1">
          <!-- Banner with Overlay Content at Bottom -->
          <div
            class="position-relative mb-3"
            style="height: 350px; border-radius: 8px; overflow: hidden"
          >
            <!-- Background Image -->
            <img
              src="https://images.unsplash.com/photo-1605296867304-46d5465a13f1?fit=crop&w=1200&q=80"
              alt="Blog Banner"
              class="w-100 h-100 object-fit-cover"
            />

            <!-- Overlay (at bottom of banner) -->
            <div
              class="position-absolute bottom-0 start-0 w-100 text-white p-4"
              style="
                background: linear-gradient(
                  to top,
                  rgba(0, 0, 0, 0.6),
                  transparent
                );
              "
            >
              <h1 class="fw-bold fs-3 mb-2">Gym Ki Kahani Vikram Ki Jubani</h1>
              <div class="d-flex align-items-center">
                <img
                  src="https://img.freepik.com/premium-vector/man-profile_1083548-15963.jpg?semt=ais_incoming&w=740&q=80"
                  class="rounded-circle me-2"
                  alt="Author"
                  style="width: 45px; height: 45px; object-fit: cover"
                />
                <div>
                  <strong>Vikram Kumar</strong><br />
                  <small>🕒 Posted on 28-May-2025 at 10:30 AM</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Views & Comments Count -->
          <div class="post-stats mb-4" style="margin-left: 1rem">
            <span id="viewsCount">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path
                  d="M12 5c-7 0-11 6-11 7s4 7 11 7 11-6 11-7-4-7-11-7zm0 12c-3.038 0-5.5-2.462-5.5-5.5S8.962 6 12 6s5.5 2.462 5.5 5.5-2.462 5.5-5.5 5.5zm0-9a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7z"
                />
              </svg>
              Views: <strong>1,245</strong>
            </span>
            <span id="commentsCount">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path
                  d="M20 2H4a2 2 0 0 0-2 2v15l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"
                />
              </svg>
              Comments: <strong id="commentsNumber">0</strong>
            </span>
          </div>

          <!-- Blog Content -->
          <div
            class="blog-content-box mb-4 p-4 rounded shadow-sm"
            style="
              background-color: #fff;
              font-family: 'Poppins', sans-serif;
              font-weight: 500;
              line-height: 1.7;
            "
          >
            <p>
              Gym ki kahani meri zubaani kuch aise shuru hoti hai — Monday aaya,
              naye week ka josh full-on tha. Naya-gym-workout-ki-yaar socha toh
              gym mein lene gaya angaare. Trainer ne bola, “Kya khaya?” Maine
              bola, “Bhai gym chalu ki hai kal se gym ka program kon join?” Aur
              agle dumbbell ka scene. Trainer ne kaha 10 kg se start karo, maine
              bola 15 hi bhalaat tight ho gaya. Socha body to banni hai bhai!
              Biceps bhi side mein gym se bolta hai, “Bhai ab gym warm-up se
              start, toh machine seekh code-chor mat kar.” 😂
            </p>
            <p>
              Diet ki baat karein to sabse badi dhokebaz hoti hai. Dost
              poochhta, “Bro kya tum gym ja rahe ho?” Maine bola, “Bhai ab food
              bhi motivation hai.” Makhe kaha, “Baan bhai, breakfast – 6 eggs,
              lunch – grilled chicken, dinner = motivation aur aasmaan ko soop.”
              Aur tabhi mummy peeche se cheekh kar boli thi, “Raat ko fridge
              mein rasgulla dhoondh raha tha kaun?” Maine bola, “Woh cheat meal
              nahi, cheat moment tha!” Sunday ko test day milta hai, sochao aj
              sirf cartoon. Tabhi phone beepa karta hai, trainer ka message aata
              hai — “Kal leg day hai”. Us moment pe bas tha hi kaha – God save
              my legs!
            </p>
            <p>
              Par ek baat sach hai – Jab gym ka dard reward lagne lage na, tabhi
              usi maza aata hai! Aur haan, kabhi kabhi rasgulla bhi chalta
              hai!... bas trainer ko pata na chale! 😅
            </p>

            <!-- Hashtags -->
            <div class="mt-3">
              <span class="badge bg-logo me-2">#GymLife</span>
              <span class="badge bg-logo me-2">#Motivation</span>
              <span class="badge bg-logo me-2">#FitnessJourney</span>
              <span class="badge bg-logo me-2">#Workout</span>
            </div>
          </div>

          <!-- Review Box -->
          <div class="review-box">
            <h5>Leave a Review</h5>
            <div class="review-stars mb-2">★ ★ ★ ★ ☆</div>
            <textarea
              class="form-control mb-2"
              rows="2"
              placeholder="Write your review..."
            ></textarea>
            <button class="btn btn-primary btn-sm">Submit Review</button>
          </div>

          <!-- Comments Section -->
          <!-- <div class="comments-box">
            <h5 class="mb-3">Comments</h5> -->

          <!-- Add Comment -->
          <!-- <form id="commentForm" class="mb-3">
              <div class="mb-2">
                <input
                  type="text"
                  id="commentName"
                  class="form-control"
                  placeholder="Your Name"
                  required
                />
              </div>
              <div class="mb-2">
                <textarea
                  id="commentText"
                  class="form-control"
                  rows="2"
                  placeholder="Your Comment"
                  required
                ></textarea>
              </div>
              <button type="submit" class="btn btn-success btn-sm">
                Post Comment
              </button>
            </form> -->

          <!-- Comment List -->
          <!-- <div id="commentList"> -->
          <!-- Comments will appear here -->
          <!-- </div> -->
          <!-- </div> -->
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 order-2 order-lg-2 mt-4 mt-lg-0">
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
                    src="https://logos-world.net/wp-content/uploads/2020/04/Nike-Logo-1971-present.jpg"
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
                🔥 Trending Blogs
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
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROIlH5YxHQlMRu94N03C8CPm-BiTXsslhx_A&s"
                    alt="Diary"
                    width="50"
                    height="50"
                    style="margin-right: 10px; border-radius: 4px"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-size: 14px; font-weight: 600"
                    >
                      Digital marketing and SEO
                    </h6>
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
                    src="https://img.freepik.com/free-psd/3d-medical-elements-with-heartbeat_23-2151202567.jpg?semt=ais_incoming&w=740&q=80"
                    alt="Speaker"
                    width="50"
                    height="50"
                    style="margin-right: 10px; border-radius: 4px"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-size: 14px; font-weight: 600"
                    >
                      Public Health and Safety
                    </h6>
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
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQohKYp4uhdFVgq79XvQV1J1FKmaeuid3NMEA&s"
                    alt="Muffler"
                    width="50"
                    height="50"
                    style="margin-right: 10px; border-radius: 4px"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-size: 14px; font-weight: 600"
                    >
                      Zudio sales
                    </h6>
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
                📌 Recent Post
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

    <!-- Bootstrap + Comment Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
      const form = document.getElementById("commentForm");
      const commentList = document.getElementById("commentList");
      const commentsNumber = document.getElementById("commentsNumber");

      // Initialize comment count
      let commentCount = 0;
      commentsNumber.textContent = commentCount;

      form.addEventListener("submit", function (e) {
        e.preventDefault();
        const name = document.getElementById("commentName").value.trim();
        const text = document.getElementById("commentText").value.trim();

        if (name && text) {
          const commentDiv = document.createElement("div");
          commentDiv.classList.add("comment-item");
          const date = new Date().toLocaleString();

          commentDiv.innerHTML = `
            <strong>${name}</strong> <small class="text-muted">(${date})</small>
            <p class="mb-0">${text}</p>
          `;

          commentList.prepend(commentDiv);
          form.reset();

          // Update comments count
          commentCount++;
          commentsNumber.textContent = commentCount;
        }
      });
    </script>
  </body>
</html>

<!-- <div class="mt-4">
  <p>
    Gym ki kahani meri zubaani kuch aise shuru hoti hai — Monday aaya, naye week
    ka josh full-on tha. Naya-gym-workout-ki-yaar socha toh gym mein lene gaya
    angaare. Trainer ne bola, “Kya khaya?” Maine bola, “Bhai gym chalu ki hai
    kal se gym ka program kon join?” Aur agle dumbbell ka scene. Trainer ne kaha
    10 kg se start karo, maine bola 15 hi bhalaat tight ho gaya. Socha body to
    banni hai bhai! Biceps bhi side mein gym se bolta hai, “Bhai ab gym warm-up
    se start, toh machine seekh code-chor mat kar.” 😂
  </p>
  <p>
    Diet ki baat karein to sabse badi dhokebaz hoti hai. Dost poochhta, “Bro kya
    tum gym ja rahe ho?” Maine bola, “Bhai ab food bhi motivation hai.” Makhe
    kaha, “Baan bhai, breakfast – 6 eggs, lunch – grilled chicken, dinner =
    motivation aur aasmaan ko soop.” Aur tabhi mummy peeche se cheekh kar boli
    thi, “Raat ko fridge mein rasgulla dhoondh raha tha kaun?” Maine bola, “Woh
    cheat meal nahi, cheat moment tha!” Sunday ko test day milta hai, sochao aj
    sirf cartoon. Tabhi phone beepa karta hai, trainer ka message aata hai —
    “Kal leg day hai”. Us moment pe bas tha hi kaha – God save my legs!
  </p>
  <p>
    Par ek baat sach hai – Jab gym ka dard reward lagne lage na, tabhi usi maza
    aata hai! Aur haan, kabhi kabhi rasgulla bhi chalta hai!... bas trainer ko
    pata na chale! 😅
  </p>
</div> -->
