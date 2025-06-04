<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="css/main.css" />
  <title>Easy Portal</title>
</head>
<body>
  <div id="loading-screen">
    <img src="image/logo.png" alt="Chargement..." />
  </div>

  <div class="header">✦Easy Portal</div>
  <div class="container" style="display: none;">
    <div class="title">Connexion</div>

    <div class="input-group">
      <input id="email" type="email" placeholder="Email" required />
    </div>

    <div class="input-group password">
      <input id="password" class="mdp" type="password" placeholder="Mot de passe" required />
      <span class="toggle-password" onclick="togglePassword()">👁</span>
    </div>

    <div>
      <button class="btn" onclick="login()">Se connecter</button>
    </div>

    <div id="message" class="message"></div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type');
      passwordInput.setAttribute('type', type === 'password' ? 'text' : 'password');
    }

    function displayMessage(msg, isError = true) {
      const messageDiv = document.getElementById('message');
      messageDiv.textContent = msg;
      messageDiv.style.color = isError ? 'red' : 'green';
    }

    async function login() {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;

      if (!email || !password) {
        displayMessage("Veuillez remplir tous les champs.");
        return;
      }

      try {
        const response = await fetch('php/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password })
        });

        const text = await response.text();
        console.log('Réponse brute :', text);

        const data = JSON.parse(text);
        console.log('Réponse JSON :', data);

        if (data.success) {
          displayMessage('Connexion réussie !', false);
          window.location.href = 'user';
        } else {
          displayMessage(data.message);
        }
      } catch (error) {
        console.error('Erreur:', error);
        displayMessage('Erreur de connexion. Veuillez réessayer.');
      }
    }

    window.onload = function() {
      fetch('php/check_session.php')
        .then(response => response.json())
        .then(data => {
          if (data.loggedIn) {
            window.location.href = 'user';
          }
        });

      setTimeout(() => {
        document.getElementById('loading-screen').style.display = 'none';
        document.querySelector('.container').style.display = 'block';
      }, 1000);
    };

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Enter') login();
    });
  </script>
</body>
</html>
