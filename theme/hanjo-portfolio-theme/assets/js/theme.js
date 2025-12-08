(function () {
  const navToggle = document.getElementById('nav-toggle');
  const body = document.body;
  const navLinks = document.querySelectorAll('.nav a');

  if (navToggle) {
    navToggle.addEventListener('click', () => {
      body.classList.toggle('nav-open');
    });
  }

  navLinks.forEach((link) => {
    link.addEventListener('click', () => body.classList.remove('nav-open'));
  });
})();
