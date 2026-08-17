
document.querySelector('.search-btn').addEventListener('click', () => {
  const query = document.querySelector('.search-box').value;
  alert(`Searching for: ${query}`);
});



  const menuToggle = document.getElementById('menuToggle');
  const nav = document.getElementById('nav');

  menuToggle.addEventListener('click', () => {
    nav.classList.toggle('open');
    document.body.classList.toggle('no-scroll');
  });


                                                                   //popular cities
const prevBtn = document.querySelector('.carousel-btn.prev');
const nextBtn = document.querySelector('.carousel-btn.next');
const cardsWrapper = document.querySelector('.cards-wrapper');

const scrollAmount = 300; // adjust for your card width

prevBtn.addEventListener('click', () => {
  cardsWrapper.scrollBy({
    left: -scrollAmount,
    behavior: 'smooth'
  });
});

nextBtn.addEventListener('click', () => {
  cardsWrapper.scrollBy({
    left: scrollAmount,
    behavior: 'smooth'
  });
});


                  // for scrolling of cities using dots
  const cards = document.querySelectorAll('.card');
  const pagination = document.querySelector('.carousel-pagination');

  // 1. Create dots
  cards.forEach((_, index) => {
    const dot = document.createElement('span');
    dot.classList.add('dot');
    if (index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => {
      cardsWrapper.scrollTo({
        left: index * cards[0].offsetWidth + index * 20, // includes margin/gap
        behavior: 'smooth'
      });
    });
    pagination.appendChild(dot);
  });

  const dots = document.querySelectorAll('.carousel-pagination .dot');

  // 2. Track scroll position to activate dots
  cardsWrapper.addEventListener('scroll', () => {
    const scrollLeft = cardsWrapper.scrollLeft;
    const cardWidth = cards[0].offsetWidth + 20; // 20 is gap
    const index = Math.round(scrollLeft / cardWidth);

    dots.forEach(dot => dot.classList.remove('active'));
    if (dots[index]) {
      dots[index].classList.add('active');
    }
  });

  
                                         //popular categories


   const container = document.getElementById('categoryContainer');
    const dotsContainer = document.getElementById('categoryDots');
    const citycards = document.querySelectorAll('.category-card');

    const citycardWidth = 280; // Adjust this if your card size changes
    const cardsPerPage = () => Math.floor(container.offsetWidth / citycardWidth);
    let totalPages = 0;

    function updateDots() {
      dotsContainer.innerHTML = '';
      totalPages = Math.ceil(citycards.length / cardsPerPage());

      for (let i = 0; i < totalPages; i++) {
        const citydot = document.createElement('div');
        citydot.classList.add('citydot');
        if (i === 0) citydot.classList.add('active');
        citydot.addEventListener('click', () => scrollToPage(i));
        dotsContainer.appendChild(citydot);
      }
    }

    function scrollToPage(pageIndex) {
      const scrollAmount = container.clientWidth * pageIndex;
      container.scrollTo({ left: scrollAmount, behavior: 'smooth' });
      setActiveDot(pageIndex);
    }

    function setActiveDot(index) {
      const citydots = document.querySelectorAll('.citydot');
      citydots.forEach((citydot, i) => {
        citydot.classList.toggle('active', i === index);
      });
    }

    function getCurrentPage() {
      return Math.round(container.scrollLeft / container.clientWidth);
    }

    container.addEventListener('scroll', () => {
      const currentPage = getCurrentPage();
      setActiveDot(currentPage);
    });

    window.addEventListener('resize', () => {
      updateDots();
    });

    window.addEventListener('load', () => {
      updateDots();
    });





                                                                    //reach customer

// Ripple Effect
    document.querySelectorAll('button').forEach(button => {
      button.addEventListener('click', function (e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');

        this.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
      });
    });

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });


                                        // features of customers

    
  const featureContainer = document.querySelector('.features-scroll-container');
  const featureDotsContainer = document.getElementById('featureDots');
  const featureCards = document.querySelectorAll('.feature-card');

  const getCardsPerPage = () => Math.floor(featureContainer.offsetWidth / featureCards[0].offsetWidth);
  let featurePages = 0;

  function updateFeatureDots() {
    featureDotsContainer.innerHTML = '';
    const perPage = getCardsPerPage();
    featurePages = Math.ceil(featureCards.length / perPage);

    for (let i = 0; i < featurePages; i++) {
      const feadot = document.createElement('div');
      feadot.classList.add('feature-dot');
      if (i === 0) feadot.classList.add('active');
      feadot.addEventListener('click', () => scrollToFeaturePage(i));
      featureDotsContainer.appendChild(feadot);
    }
  }

  function scrollToFeaturePage(index) {
    const scrollValue = featureContainer.clientWidth * index;
    featureContainer.scrollTo({ left: scrollValue, behavior: 'smooth' });
    setActiveFeatureDot(index);
  }

  function setActiveFeatureDot(index) {
    const feadots = document.querySelectorAll('.feature-dot');
    feadots.forEach((feadot, i) => {
      feadot.classList.toggle('active', i === index);
    });
  }

  function getCurrentFeaturePage() {
    return Math.round(featureContainer.scrollLeft / featureContainer.clientWidth);
  }

  featureContainer.addEventListener('scroll', () => {
    setActiveFeatureDot(getCurrentFeaturePage());
  });

  window.addEventListener('resize', updateFeatureDots);
  window.addEventListener('load', updateFeatureDots);


                                                    //testimonials


 class TestimonialCarousel {
    constructor() {
        this.currentIndex = 0;
        this.isAutoPlaying = true;
        this.autoPlayInterval = null;
        this.testimonials = document.querySelectorAll('.testimonial-card');
        this.track = document.getElementById('testTrack');
        this.indicators = document.querySelectorAll('.indicator');
        this.autoPlayBtn = document.getElementById('autoPlayBtn');
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.startAutoPlay();
        this.animateStats();
        this.setupVideoPlayers();
    }
    
    setupEventListeners() {
        // Navigation buttons
        this.prevBtn.addEventListener('click', () => this.previousSlide());
        this.nextBtn.addEventListener('click', () => this.nextSlide());
        
        // Auto play toggle
        this.autoPlayBtn.addEventListener('click', () => this.toggleAutoPlay());
        
        // Progress indicators
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });
        
        // Play button clicks
        document.querySelectorAll('.play-button').forEach((btn, index) => {
            btn.addEventListener('click', () => this.playVideo(index));
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.previousSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
            if (e.key === ' ') {
                e.preventDefault();
                this.toggleAutoPlay();
            }
        });
        
        // Touch/swipe support
        this.setupTouchEvents();
    }
    
    setupTouchEvents() {
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        this.track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            this.pauseAutoPlay();
        });
        
        this.track.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });
        
        this.track.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            
            const diff = startX - currentX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.nextSlide();
                } else {
                    this.previousSlide();
                }
            }
            
            if (this.isAutoPlaying) {
                this.startAutoPlay();
            }
        });
    }
    
    setupVideoPlayers() {
        this.testimonials.forEach((card, index) => {
            const videoId = card.dataset.video;
            const iframe = card.querySelector('iframe');
            iframe.src = `https://www.youtube.com/embed/${videoId}?enablejsapi=1`;
        });
    }
    
    goToSlide(index) {
        this.currentIndex = index;
        this.updateCarousel();
        this.updateIndicators();
        this.closeAllVideos();
    }
    
    nextSlide() {
        this.currentIndex = (this.currentIndex + 1) % this.testimonials.length;
        this.updateCarousel();
        this.updateIndicators();
        this.closeAllVideos();
    }
    
    previousSlide() {
        this.currentIndex = this.currentIndex === 0 ? this.testimonials.length - 1 : this.currentIndex - 1;
        this.updateCarousel();
        this.updateIndicators();
        this.closeAllVideos();
    }
    
    updateCarousel() {
        const cardWidth = 350;
        const gap = 30;
        const offset = this.currentIndex * (cardWidth + gap);
        
        this.track.style.transform = `translateX(-${offset}px)`;
        
        // Update active states
        this.testimonials.forEach((card, index) => {
            card.classList.toggle('active', index === this.currentIndex);
        });
        
        // Add entrance animation
        this.testimonials[this.currentIndex].style.animation = 'slideIn 0.6s ease-out';
        setTimeout(() => {
            this.testimonials[this.currentIndex].style.animation = '';
        }, 600);
    }
    
    updateIndicators() {
        this.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentIndex);
        });
    }
    
    startAutoPlay() {
        if (this.autoPlayInterval) return;
        
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, 4000);
    }
    
    pauseAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
    
    toggleAutoPlay() {
        this.isAutoPlaying = !this.isAutoPlaying;
        
        if (this.isAutoPlaying) {
            this.startAutoPlay();
            this.autoPlayBtn.innerHTML = '<i class="fas fa-pause"></i><span>Auto Play</span>';
            this.autoPlayBtn.classList.remove('paused');
        } else {
            this.pauseAutoPlay();
            this.autoPlayBtn.innerHTML = '<i class="fas fa-play"></i><span>Auto Play</span>';
            this.autoPlayBtn.classList.add('paused');
        }
    }
    
    playVideo(index) {
        const card = this.testimonials[index];
        const placeholder = card.querySelector('.video-placeholder');
        const videoFrame = card.querySelector('.video-frame');
        const iframe = card.querySelector('iframe');
        const videoId = card.dataset.video;
        
        // Update iframe src to autoplay
        iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&enablejsapi=1`;
        
        // Hide placeholder and show video
        placeholder.style.display = 'none';
        videoFrame.style.display = 'block';
        
        // Pause auto play when video starts
        this.pauseAutoPlay();
        this.autoPlayBtn.innerHTML = '<i class="fas fa-play"></i><span>Auto Play</span>';
        this.autoPlayBtn.classList.add('paused');
        this.isAutoPlaying = false;
    }
    
    closeAllVideos() {
        this.testimonials.forEach(card => {
            const placeholder = card.querySelector('.video-placeholder');
            const videoFrame = card.querySelector('.video-frame');
            const iframe = card.querySelector('iframe');
            const videoId = card.dataset.video;
            
            // Reset iframe src
            iframe.src = `https://www.youtube.com/embed/${videoId}?enablejsapi=1`;
            
            // Show placeholder and hide video
            placeholder.style.display = 'block';
            videoFrame.style.display = 'none';
        });
    }
    
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .testimonial-card.active {
        animation: pulse 2s ease-in-out infinite;
    }
`;
document.head.appendChild(style);

// Initialize carousel when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new TestimonialCarousel();
});

// Add smooth scrolling behavior
document.documentElement.style.scrollBehavior = 'smooth';
                                     

                                              // footer

     function validateCaptcha() {
    // This is just a placeholder. Add backend captcha logic for real security.
    const input = document.getElementById('captchaInput').value.trim();
    if (!input || input.length < 3) {
      alert("Please enter the correct captcha.");
      return false;
    }
    return true;
  }



  
  const scrollContainer = document.querySelector('.features-scroll-container');
  const leftBtn = document.querySelector('.left-btn-fe');
  const rightBtn = document.querySelector('.right-btn-fe');

  leftBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({
      left: -300,
      behavior: 'smooth'
    });
  });

  rightBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({
      left: 300,
      behavior: 'smooth'
    });
  });

