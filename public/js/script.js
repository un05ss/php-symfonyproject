// Effet de bienvenue animé
document.addEventListener("DOMContentLoaded", () => {
  const title = document.querySelector("h2");
  if (title) {
    title.style.opacity = 0;
    setTimeout(() => {
      title.style.transition = "opacity 1.5s ease";
      title.style.opacity = 1;
    }, 300);
  }
});

// Animation des cartes statistiques
const cards = document.querySelectorAll(".card");
cards.forEach(card => {
  card.addEventListener("mouseover", () => {
    card.style.transform = "rotateY(5deg)";
  });
  card.addEventListener("mouseout", () => {
    card.style.transform = "rotateY(0deg)";
  });
});