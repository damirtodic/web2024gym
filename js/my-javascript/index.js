/**
 * *LOGIN
 */
// $(document).ready(function () {
//   // Login AJAX call
//   $("#loginForm").submit(function (e) {
//     e.preventDefault();

//     var email = $("#email").val();
//     var password = $("#password").val();

//     $.getJSON("users.json", function (data) {
//       var users = data.users;
//       var loggedInUser = users.find(function (user) {
//         return user.email === email && user.password === password;
//       });

//       if (loggedInUser) {
//         // Store data in local storage
//         localStorage.setItem("userId", loggedInUser.id);
//         localStorage.setItem("userName", loggedInUser.name);

//         // Hide buttons
//         $(".auth-buttons-holder").addClass("hidden");
//         $(".top-option").addClass("hidden");
//         $(".logout-button-div").css("display", "flex");
//         $("#user-name").text(loggedInUser.name);

//         //Close log in modal
//         closeModal();
//       } else {
//         alert("Invalid email or password");
//       }
//     }).fail(function () {
//       alert("Error loading users data");
//     });
//   });
// });

/**
 * *SPAPP NAVIGATION
 */
$(document).ready(function () {
  // Function to load dynamic content SPapp
  function loadContent(url) {
    if (!url.includes("img")) {
      $.get(url, function (data) {
        $("#dynamic-content").html(data);
      }).fail(function () {
        alert("Error loading the page");
      });
    }
  }

  // Load default content
  loadContent("home.html");

  // Handle navigation and assign active class
  $(document).on("click", "a", function (e) {
    e.preventDefault();
    var page = $(this).attr("href");
    loadContent(page);
    $(".nav-menu ul li").removeClass("active");
    $(this).closest("li").addClass("active");
  });
});

/**
 * *CHECK LOGGED IN USER
 */

$(document).ready(function () {
  function checkLocalStorage() {
    var userId = localStorage.getItem("userId");
    var userName = localStorage.getItem("userName");
    if (userId && userName) {
      $("#user-name").text(userName);
      $(".top-option").addClass("hidden");
      $(".auth-buttons-holder").addClass("hidden");
      $(".logout-button-div").css("display", "flex");
    } else {
      $(".logout-button-div").css("display", "none");
    }
  }

  checkLocalStorage();
});

/**
 * ! AUTH MODAL FUNCTIONS
 */

function openModal() {
  document.getElementById("myModal").style.display = "block";
  document.getElementById("overlay").style.display = "block";
}

function closeModal() {
  document.getElementById("myModal").style.display = "none";
  document.getElementById("overlay").style.display = "none";
}
function openRegisterModal() {
  document.getElementById("registerModal").style.display = "block";
  document.getElementById("overlay").style.display = "block";
}
function closeRegisterModal() {
  document.getElementById("registerModal").style.display = "none";
  document.getElementById("overlay").style.display = "none";
}
function logout() {
  localStorage.removeItem("userId");
  localStorage.removeItem("userEmail");
  $(".auth-buttons-holder").removeClass("hidden");
  $(".top-option").removeClass("hidden");
  $(".logout-button-div").css("display", "none");
}
function enrollNow() {
  var userId = localStorage.getItem("userId");
  var userName = localStorage.getItem("userName");
  if (userId && userName) {
    return;
  } else {
    openModal();
  }
}
