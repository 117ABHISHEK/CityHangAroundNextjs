document.querySelector('.search-btn').addEventListener('click', () => {
  const query = document.querySelector('.search-input').value;
  alert(`Searching for: ${query}`);
});
   


                                                //testimonals
 const container = document.getElementById("scrollContainer");

    function scrollLeft() {
      container.scrollBy({ left: -300, behavior: 'smooth' });
    }

    function scrollRight() {
      container.scrollBy({ left: 300, behavior: 'smooth' });
    }

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