// REGISTER

$(document).ready(function () {
  $("#registerForm").validate({
    rules: {
      email: {
        required: true,
        email: true,
      },
      name: {
        required: true,
      },
      dob: {
        required: true,
        date: true,
      },
      password: {
        required: true,
        minlength: 6,
      },
      confirmPassword: {
        required: true,
        minlength: 6,
        equalTo: "#password", // Ensure confirmPassword matches the password field
      },
    },
    messages: {
      email: {
        required: "Please enter your email",
        email: "Please enter a valid email address",
      },
      name: {
        required: "Please enter your name and surname",
      },
      dob: {
        required: "Please enter your date of birth",
        date: "Please enter a valid date",
      },
      password: {
        required: "Please enter a password",
        minlength: "Your password must be at least 6 characters long",
      },
      confirmPassword: {
        required: "Please confirm your password",
        minlength: "Your password must be at least 6 characters long",
        equalTo: "Passwords do not match",
      },
    },
    submitHandler: function (form) {
      alert("submitano");
    },
  });
});

// LOGIN

$(document).ready(function () {
  $("#loginForm").validate({
    rules: {
      "login-email": {
        required: true,
        email: true,
      },
      "login-password": {
        required: true,
        minlength: 6,
      },
    },
    messages: {
      "login-email": {
        required: "Please enter your email",
        email: "Please enter a valid email address",
      },
      "login-password": {
        required: "Please enter your password",
        minlength: "Your password must be at least 6 characters long",
      },
    },
    submitHandler: function (form, event) {
      event.preventDefault();
      var email = $("#login-email").val();
      var password = $("#login-password").val();
      $.getJSON("users.json", function (data) {
        var users = data.users;
        var loggedInUser = users.find(function (user) {
          return user.email === email && user.password === password;
        });

        if (loggedInUser) {
          localStorage.setItem("userId", loggedInUser.id);
          localStorage.setItem("userName", loggedInUser.name);

          // Hide buttons
          $(".auth-buttons-holder").addClass("hidden");
          $(".top-option").addClass("hidden");
          $(".logout-button-div").css("display", "flex");
          $("#user-name").text(loggedInUser.name);

          // Close modal
          closeModal();
        } else {
          alert("Invalid email or password");
        }
      }).fail(function () {
        alert("Error loading users data");
      });
    },
  });
});
