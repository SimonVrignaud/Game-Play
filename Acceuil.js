// @ts-nocheck
"use strict";

const nav = document.querySelector("header"); 
const carousel = document.querySelector(".carousel");
const images = document.querySelectorAll(".carousel img");
const totalImages = images.length;

/* Ici, on fera descendre la barre de Navigation du site afin qu'elle soit visible des que l'utilisateur aura scrollé vers le bas de plus de 120 pixels. / Here, we have the part of the code that will drop the Navigation bar from the top of the screen as soon as the user had scroll 120 pixels down.*/ 
window.addEventListener("scroll", () => {
  if (window.scrollY > 120) {
    nav.style.top = 0;
  } else {
    nav.style.top = "-70px";
  }
});

const trgl = document.querySelector(".conteneurTriangle");

window.addEventListener("scroll", () => {
  if (window.scrollY > 200) {
    trgl.style.left = 0;
  } else {
    trgl.style.left = "-1500px";
  }
});

let index = 0; /* Initialisation de la variable index à 0 */ 

document.getElementById("next").addEventListener("click", () => {
  index = (index + 1) % totalImages;
  updateCarousel();
});
/* Récuperation de l'élément next puis écoute de lévénement click,
incrémentation d' "index" représentant l'image affichée 
et index + 1 permettra de changer d'image dans un sens. % total d'images qui 
permettra de faire une boucle infinie. updatecarousel appelle la fonction pour changer d'image.*/ 

document.getElementById("prev").addEventListener("click", () => {
  index = (index - 1 + totalImages) % totalImages;
  updateCarousel();
});
/* Meme logique que precedement mais avec un "index - 1 afin de faire tourner l'mage dans l'autre sens" */ 

function updateCarousel() {
  carousel.style.transform = `translateX(-${(index * 100) / totalImages}%)`;
}
/* fonction qui permettra le mouvement des images du caroussel.
Translate X pour l'axe de rotation, - pour initier un mouvement vers la gauche,
index * 100 / totalimages indique le pourcentage de déplacement par rapport à l'image*/