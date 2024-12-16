const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', () => {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);
  
  // Toggle eye icon color
  togglePassword.setAttribute('fill', type === 'text' ? '#4a90e2' : '#bbb');
  togglePassword.setAttribute('stroke', type === 'text' ? '#4a90e2' : '#bbb');
});