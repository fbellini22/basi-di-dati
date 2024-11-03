<?php
//questa è la homepage del sito
require_once('connect.php');

?>
<html>
<head>
  <title>CrowdConnect</title>
  <link rel="stylesheet" href="index.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
</head>
<body>
  <div class="hero">
    <div class="form-box">
      <div class="button-box">
        <div id="btn"></div>
        <button type="button" class="toggle-btn">Login</button>
        <button type="button" class="toggle-btn">Registrati</button>
      </div>
      <form id="login" name="login" class="input-group">
        <label for="logusername">Username:</label>
        <input type="text" class="input-field" name="logusername" id="logusername" required>

        <label for="logpw">Password:</label>
        <input type="password" class="input-field" name="logpw" id="logpw" required>
        <button id="button_log" class="submit-btn">Login</button>
      </form>

      <form id="register" name='register' class="input-group">
        <label for="Nome">Nome:</label>
        <input type="text" class="input-field" name='Nome' id="Nome" required>
        
        <label for="Cognome">Cognome:</label>
        <input type="text" class="input-field"  name='Cognome'id="Cognome" required>

        <label for="Mail">Mail:</label>
        <input type="text" class="input-field" name='Mail' id="Mail" required>

        <label for="Usernamereg">Username:</label>
        <input type="text" class="input-field" name='Username' id="Usernamereg" required>

        <label for="Pw">Password:</label>
        <input type="password" class="input-field"  name='Pw' id="Pw" required>

        <label for="Pwc">Conferma Password:</label>
        <input type="password" class="input-field"  name='Pwc' id="Pwc" required>

        <input type="checkbox" class="chech-box" id="accetto" name="accetto"><label for="accetto">Accetto i termini e le condizioni</label>
        <button id="button_reg" class="submit-btn">Registrati</button>
      </form>
    </div>
  </div>
  <script src="jslogin.js"></script>
  <script src= "jsregistrazione.js"> </script>
  <script src="index.js"></script>
</body>
</html>
