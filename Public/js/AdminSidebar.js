const sidebar = document.getElementById("sidebar");
const items = document.querySelectorAll("#sidebar .sidebar__item");
items.forEach((child, index) => {
  const link = child.querySelector("a");
  link.addEventListener("click", () => {
    const submenu = child.querySelector(".sidebar__submenu");
    if (!submenu) return;
    submenu.classList.toggle("sidebar__submenu--active");
  });
});
