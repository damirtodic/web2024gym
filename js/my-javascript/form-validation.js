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
        equalTo: "#password",
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
      var formData = $(form).serialize();
      console.log(formData);
      $.ajax({
        type: "POST",
        url: "http://localhost/web-2024/web2024gym/backend/register",
        data: formData,
        dataType: "json",
        success: function (response) {
          closeRegisterModal();
          alert("User registered successfully!");
        },
        error: function (xhr, status, error) {
          if (xhr.responseText) {
            alert("Error registering user: " + xhr.responseText);
          }
        },
      });
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

      $.ajax({
        type: "POST",
        url: "http://localhost/web-2024/web2024gym/backend/login",
        data: { email: email, password: password },
        dataType: "json",
        success: function (response) {
          if (response.data) {
            localStorage.setItem("userId", response.data.id);
            localStorage.setItem("userName", response.data.name);
            localStorage.setItem("token", response.data.token);
            if (response.data.role_id === 1) {
              window.location.href = "admin.html";
            }

            // Hide buttons
            $(".auth-buttons-holder").addClass("hidden");
            $(".top-option").addClass("hidden");
            $(".logout-button-div").css("display", "flex");
            $("#user-name").text(response.data.name);

            // Close modal
            closeModal();
          } else {
            alert("Invalid email or password");
          }
        },
        error: function () {
          alert("Error logging in");
        },
      });
    },
  });
});
