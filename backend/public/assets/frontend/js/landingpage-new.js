// crousal for popular citys --------------------------------------------------------

document.addEventListener("DOMContentLoaded", () => {
  const wrapper = document.querySelector(".cards-wrapper");
  const cards = document.querySelectorAll(".listing-card");
  const prevBtn = document.querySelector(".carousel-btn.prev");
  const nextBtn = document.querySelector(".carousel-btn.next");
  const pagination = document.querySelector(".carousel-pagination");

  if (!wrapper || cards.length === 0) return;

  let currentPage = 0;

  // Determine scroll per page based on screen width
  function getPageWidth() {
    if (window.innerWidth < 768) {
      // Mobile: scroll by 1 card
      return cards[0].offsetWidth + 20; // card + margin
    } else {
      // Desktop: scroll by visible area
      return wrapper.clientWidth;
    }
  }

  function getTotalPages() {
    return Math.ceil(wrapper.scrollWidth / getPageWidth());
  }

  // Render dots
  function renderDots() {
    pagination.innerHTML = "";
    const totalPages = getTotalPages();
    for (let i = 0; i < totalPages; i++) {
      const dot = document.createElement("span");
      dot.classList.add("dot");
      if (i === 0) dot.classList.add("active");
      dot.addEventListener("click", () => goToPage(i));
      pagination.appendChild(dot);
    }
  }

  function updateButtons() {
    const totalPages = getTotalPages();
    prevBtn.style.display = currentPage === 0 ? "none" : "block";
    nextBtn.style.display = currentPage >= totalPages - 1 ? "none" : "block";

    const dots = pagination.querySelectorAll(".dot");
    dots.forEach((dot, i) => dot.classList.toggle("active", i === currentPage));
  }

  function goToPage(page) {
    const totalPages = getTotalPages();
    currentPage = Math.max(0, Math.min(page, totalPages - 1));
    wrapper.scrollTo({
      left: getPageWidth() * currentPage,
      behavior: "smooth",
    });
    updateButtons();
  }

  prevBtn.addEventListener("click", () => goToPage(currentPage - 1));
  nextBtn.addEventListener("click", () => goToPage(currentPage + 1));

  window.addEventListener("resize", () => {
    renderDots();
    goToPage(currentPage); // keep current page in view
  });

  // Initialize
  renderDots();
  updateButtons();
});

// hero section ------------------------------------------------------------
// Wait for DOM to be ready
document.addEventListener("DOMContentLoaded", function () {
  // Mouse follower effect
  const mouseLight = document.getElementById("mouseLight");
  let mouseX = 0;
  let mouseY = 0;

  document.addEventListener("mousemove", function (e) {
    mouseX = e.clientX;
    mouseY = e.clientY;

    if (mouseLight) {
      mouseLight.style.left = mouseX - 192 + "px";
      mouseLight.style.top = mouseY - 192 + "px";
    }
  });

  // 3D tilt effect on hero content
  const heroContent = document.getElementById("heroContent");

  if (heroContent) {
    document.addEventListener("mousemove", function (e) {
      const xAxis = (window.innerWidth / 2 - e.clientX) / 100;
      const yAxis = (window.innerHeight / 2 - e.clientY) / 100;

      heroContent.style.transform = `perspective(1000px) rotateX(${yAxis}deg) rotateY(${-xAxis}deg)`;
    });
  }

  // Generate animated particles

  // Trigger entrance animations
  setTimeout(function () {
    const badge = document.getElementById("heroBadge");
    const title = document.getElementById("heroTitle");
    const subtitle = document.getElementById("heroSubtitle");
    const buttons = document.getElementById("heroButtons");

    if (badge) badge.style.opacity = "1";
    if (title) title.style.opacity = "1";
    if (subtitle) subtitle.style.opacity = "1";
    if (buttons) buttons.style.opacity = "1";
  }, 100);

  // Button click ripple effect
  const buttons = document.querySelectorAll(".btn-hero");

  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const ripple = document.createElement("span");
      ripple.className = "click-ripple";

      const rect = button.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top - size / 2;

      ripple.style.width = ripple.style.height = size + "px";
      ripple.style.left = x + "px";
      ripple.style.top = y + "px";

      button.appendChild(ripple);

      setTimeout(() => ripple.remove(), 600);
    });
  });

  // Add click ripple styles
  const rippleStyle = document.createElement("style");
  rippleStyle.textContent = `
        .click-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
  document.head.appendChild(rippleStyle);

  // Smooth scroll for internal links (if any)
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Performance optimization: pause animations when page is not visible
  document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
      document.body.style.animationPlayState = "paused";
    } else {
      document.body.style.animationPlayState = "running";
    }
  });
});

// features cars section --------------------------------------------------
// Add intersection observer for scroll animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -50px 0px",
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.style.animationPlayState = "running";
    }
  });
}, observerOptions);

// Observe all feature cards
document.querySelectorAll(".feature-card").forEach((card) => {
  card.style.animationPlayState = "paused";
  observer.observe(card);
});

// Add click interaction to buttons
document.querySelectorAll(".btn-feature").forEach((button) => {
  button.addEventListener("click", function (e) {
    // Create ripple effect
    const ripple = document.createElement("span");
    ripple.classList.add("ripple");
    this.appendChild(ripple);

    const rect = this.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;

    ripple.style.width = ripple.style.height = size + "px";
    ripple.style.left = x + "px";
    ripple.style.top = y + "px";

    setTimeout(() => {
      ripple.remove();
    }, 600);
  });
});

// FAQ section ------------------------------------------------------------

const faqItems = document.querySelectorAll(".faq li");

faqItems.forEach((item) => {
  const link = item.querySelector("a");
  link.addEventListener("click", () => {
    const isActive = item.classList.contains("active");

    faqItems.forEach((otherItem) => {
      otherItem.classList.remove("active");
    });

    if (!isActive) {
      item.classList.add("active");
    }
  });
});

// script for testinomials /

document.addEventListener("DOMContentLoaded", function () {
  const testimonialCards = document.querySelectorAll(".testimonial-card");

  testimonialCards.forEach((card) => {
    const playButton = card.querySelector(".play-overlay");
    const placeholder = card.querySelector(".video-placeholder");
    const videoFrame = card.querySelector(".video-frame");
    const video = videoFrame.querySelector("video");

    playButton.addEventListener("click", function () {
      testimonialCards.forEach((otherCard) => {
        if (otherCard !== card) {
          const otherPlaceholder =
            otherCard.querySelector(".video-placeholder");
          const otherVideoFrame = otherCard.querySelector(".video-frame");
          const otherVideo = otherVideoFrame.querySelector("video");
          otherVideo.pause();
          otherVideo.currentTime = 0;
          otherPlaceholder.style.display = "block";
          otherVideoFrame.style.display = "none";
        }
      });
      placeholder.style.display = "none";
      videoFrame.style.display = "block";
      video.play();

      video.onended = function () {
        video.pause();
        video.currentTime = 0;
        placeholder.style.display = "block";
        videoFrame.style.display = "none";
      };
    });
  });
});




