// Dark mode toggle
const themeToggle = document.getElementById('theme-toggle');
const themeIcon = document.getElementById('theme-icon');
const applyTheme = (theme) => {
 if (theme === 'dark') {
  document.documentElement.classList.add('dark');
  themeIcon.classList.replace('bx-moon', 'bx-sun');
 } else {
  document.documentElement.classList.remove('dark');
  themeIcon.classList.replace('bx-sun', 'bx-moon');
 }
};
const savedTheme = localStorage.getItem('theme');
applyTheme(savedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
themeToggle.addEventListener('click', () => {
 const newTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
 localStorage.setItem('theme', newTheme);
 applyTheme(newTheme);
});