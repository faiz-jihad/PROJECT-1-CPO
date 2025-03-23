// sidebar.js

document.addEventListener("DOMContentLoaded", function () {
  const menuButton = document.getElementById("menu-button");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const closeButton = document.getElementById("close-button");

  // Buka sidebar saat tombol diklik
  menuButton.addEventListener("click", function () {
    sidebar.classList.add("open");
    overlay.classList.add("show");
  });

  // Tutup sidebar saat klik overlay atau tombol close
  overlay.addEventListener("click", function () {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
  });

  // Tangkap pilihan lapangan
  document.querySelectorAll(".card-title").forEach((item) => {
    item.addEventListener("click", function () {
      const selectedField = this.textContent;
      localStorage.setItem("selectedField", selectedField);
      window.location.href = "halamanbooking.html"; // Redirect ke halaman booking
    });
  });
});

// sub menu
let subMenu = document.getElementById("subMenu");

function toggleMenu() {
  subMenu.classList.toggle("open-menu");
}

// slider
let list = document.querySelector(".slider .list");
let items = document.querySelectorAll(".slider .list .item");
let dots = document.querySelectorAll(".slider .dots li");
let prev = document.getElementById("prev");
let next = document.getElementById("next");

let active = 0;
let lengthItems = items.length - 1;

next.onclick = function () {
  if (active + 1 > lengthItems) {
    active = 0;
  } else {
    active = active + 1;
  }
  reloadSlider();
};

prev.onclick = function () {
  if (active - 1 < 0) {
    active = lengthItems;
  } else {
    active = active - 1;
  }
  reloadSlider();
};

let refreshSlider = setInterval(() => {
  next.click();
}, 5000);

function reloadSlider() {
  let checkLeft = items[active].offsetLeft;
  list.style.left = -checkLeft + "px";

  let lastActiveDot = document.querySelector(".slider .dots li.active");
  lastActiveDot.classList.remove("active");
  dots[active].classList.add("active");
  clearInterval(refreshSlider);
  refreshSlider = setInterval(() => {
    next.click();
  }, 5000);
}

dots.forEach((li, key) => {
  li.addEventListener("click", function () {
    active = key;
    reloadSlider();
  });
});
// Dummy user login check (Replace with real authentication check)
const user = {
  isLoggedIn: true, // Change to false to simulate non-logged-in user
  username: "Asep hytam",
};

// Load existing comments (dummy data)
const comments = [
  { username: "", text: "" },
  { username: "", text: "" },
];

// Display comments
function loadComments() {
  const commentsList = document.getElementById("comments-list");
  commentsList.innerHTML = "";

  comments.forEach((comment) => {
    const commentDiv = document.createElement("div");
    commentDiv.classList.add("comment");
    commentDiv.innerHTML = `
          <strong>${comment.username}</strong>
          <p>${comment.text}</p>
      `;
    commentsList.appendChild(commentDiv);
  });
}

// Handle comment submission
document.getElementById("commentForm").addEventListener("submit", function (e) {
  e.preventDefault();

  if (!user.isLoggedIn) {
    alert("You must be logged in to leave a comment.");
    return;
  }

  const commentText = document.getElementById("commentText").value;
  if (commentText.trim() === "") return;

  // Add new comment
  comments.push({ username: user.username, text: commentText });
  loadComments();
  document.getElementById("commentText").value = "";
});

// Initialize comments
window.onload = loadComments;
