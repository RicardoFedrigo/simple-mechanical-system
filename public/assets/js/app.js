document.addEventListener("DOMContentLoaded", () => {
  const toast = document.querySelector("[data-toast]");
  if (toast) {
    new bootstrap.Toast(toast).show();
  }
});
