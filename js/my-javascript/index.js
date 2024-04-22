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
  window.location.reload();
}

function refetchSubscriptions() {
  localStorage.setItem("preventEnroll", "false");
  $.ajax({
    url: "http://localhost/web-2024/web2024gym/backend/fetch_subscriptions.php",
    type: "GET",
    dataType: "json",
    success: function (data) {
      populatePricingItems(data);
    },
    error: function () {
      console.log("Error fetching subscriptions.");
    },
  });
  var userId = localStorage.getItem("userId");
  if (userId) {
    // Fetch active subscription IDs for the user
    $.ajax({
      url: "http://localhost/web-2024/web2024gym/backend/fetch_subscription_by_userId.php",
      type: "POST",
      data: { user_id: userId },
      dataType: "json",
      success: function (data) {
        if (data.error) {
          console.log("Error: " + data.error);
        } else {
          console.log(data);
          disableButtons(data.subscriptions);
        }
      },
      error: function () {
        console.log("Error fetching active subscription IDs.");
      },
    });
  }

  function disableButtons(activeSubscription) {
    for (i = 1; i <= 3; i++) {
      $("#enrollBtn" + i).text("Already Enrolled");
    }
    activeSubscription.forEach(function (subscription) {
      localStorage.setItem("preventEnroll", true);
      var subscriptionId = subscription.subscription_id;
      var expirationDate = subscription.expiration_date;
      if (expirationDate) {
        console.log("expiration date good");
        var expirationText = "Subscribed until " + expirationDate;
        var paragraphHtml = `<p id='subscribed-until'>${expirationText}</p>`;
        $("#enrollBtn" + subscriptionId).after(paragraphHtml);
      }
    });
  }

  function populatePricingItems(subscriptions) {
    var container = $("#pricing-items-container");
    subscriptions.forEach(function (subscription) {
      var subscriptionId = subscription.id;
      var subscriptionType = subscription.subscription_type;
      var subscriptionPrice = subscription.price;

      var itemHtml = `
      <div class="col-lg-4 col-md-8">
        <div class="ps-item">
          <h3>${subscriptionType}</h3>
          <div class="pi-price">
            <h2>$ ${subscriptionPrice}</h2>
            <span>START TODAY</span>
          </div>
          <ul>
            <li>Free riding</li>
            <li>Unlimited equipments</li>
            <li>Personal trainer</li>
            <li>Weight losing classes</li>
            <li>Month to mouth</li>
            <li>No time restriction</li>
          </ul>
          <a id="enrollBtn${subscriptionId}" class="primary-btn pricing-btn" onclick="enrollNow(${subscriptionId})">Enroll now</a>
        </div>
      </div>
    `;
      container.append(itemHtml);
    });
  }
}
function enrollNow(subscriptionId) {
  var userId = localStorage.getItem("userId");
  var userName = localStorage.getItem("userName");
  var preventEnroll = localStorage.getItem("preventEnroll");
  if (preventEnroll === "false") {
    if (userId && userName) {
      $.ajax({
        url: "http://localhost/web-2024/web2024gym/backend/add_subscription.php",
        type: "POST",
        data: {
          user_id: userId,
          subscription_id: subscriptionId,
        },
        dataType: "json",
        success: function (response) {
          console.log(response.message);
          refetchSubscriptions();
          // You can perform additional actions after successful subscription creation
        },
        error: function (xhr, status, error) {
          console.error("Error creating subscription:", error);
        },
      });
    } else {
      openModal();
    }
  }
}
