$(document).ready(function() {
    // Seleziona il form di login, il form di registrazione e il bottone
    var loginForm = $("#login");
    var registerForm = $("#register");
    var btn = $("#btn");

    // Definisco la funzione per mostrare il form di registrazione
    function register() {
      loginForm.animate({left: "-400px"});
      registerForm.animate({left: "50px"});
      btn.animate({left: "110px"});
    }

    // Definisco la funzione per mostrare il form di login
    function login() {
      loginForm.animate({left: "50px"});
      registerForm.animate({left: "450px"});
      btn.animate({left: "0"});
    }

    // Quando vengono cliccati i bottoni "Login" e "register", esegui la funzione "login" o "register"
    $(".toggle-btn:eq(0)").on("click", function() {
      login();
    });

    $(".toggle-btn:eq(1)").on("click", function() {
      register();
    });
  });