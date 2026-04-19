const swiper = new Swiper(".swiper", {
  direction: "horizontal",
  loop: true,

  // Autoplay settings
  autoplay: {
    delay: 3000, // Change slide every 3 seconds
    disableOnInteraction: false, // Keep autoplay working even after manual interaction
  },

  // Pagination
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },

  // Navigation arrows
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});


document.getElementById("login").addEventListener("click", function(){
  document.querySelector(".popup").style.display = "flex";
})

document.querySelector(".form-container_register-container i". addEventListener("click", function(){
  document.querySelector(".popup").style.display = "none";
}))