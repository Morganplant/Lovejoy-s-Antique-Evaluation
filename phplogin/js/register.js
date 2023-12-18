function checkPasswordStrength() {
  var password = document.getElementById("password").value;
  var strengthIndicator = document.getElementById(
    "password-strength-indicator",
  );
  var strength = 0;

  // Check password length
  if (password.length >= 8) {
    strength += 1;
  }

  // Check for uppercase letters
  if (/[A-Z]/.test(password)) {
    strength += 1;
  }

  // Check for lowercase letters
  if (/[a-z]/.test(password)) {
    strength += 1;
  }

  // Check for numbers
  if (/[0-9]/.test(password)) {
    strength += 1;
  }

  // Check for special characters
  if (/[^A-Za-z0-9]/.test(password)) {
    strength += 1;
  }

  // Update strength indicator
  switch (strength) {
    case 0:
      strengthIndicator.innerHTML = "";
      break;
    case 1:
      strengthIndicator.innerHTML = "Weak";
      break;
    case 2:
      strengthIndicator.innerHTML = "Fair";
      break;
    case 3:
      strengthIndicator.innerHTML = "Good";
      break;
    case 4:
      strengthIndicator.innerHTML = "Strong";
      break;
    case 5:
      strengthIndicator.innerHTML = "Very Strong";
      break;
  }
}
