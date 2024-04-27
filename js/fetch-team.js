$(document).ready(function () {
  // Function to fetch team data from JSON file
  function fetchTeamData() {
    $.ajax({
      url: "http://localhost/web-2024/web2024gym/backend/fetch_employees.php",
      dataType: "json",
      success: function (data) {
        console.log(data);
        // Populate team members on the page
        if (data && data.team && data.team.length > 0) {
          data.team.forEach(function (member) {
            var memberHtml =
              '<div class="col-lg-4 col-sm-6">' +
              '<div class="ts-item set-bg" style="background-image: url(' +
              member.image +
              ')">' +
              '<div class="ts_text">' +
              "<h4>" +
              member.name +
              "</h4>" +
              "<span>" +
              member.position +
              "</span>" +
              '<div class="tt_social">' +
              '<a href="' +
              member.social.facebook +
              '"><i class="fa fa-facebook"></i></a>' +
              '<a href="' +
              member.social.twitter +
              '"><i class="fa fa-twitter"></i></a>' +
              '<a href="' +
              member.social.youtube +
              '"><i class="fa fa-youtube-play"></i></a>' +
              '<a href="' +
              member.social.instagram +
              '"><i class="fa fa-instagram"></i></a>' +
              '<a href="' +
              member.social.email +
              '"><i class="fa fa-envelope-o"></i></a>' +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>";
            $(".team-section .container .row").append(memberHtml);
          });
        }
      },
      error: function () {
        console.log("Error fetching team data");
      },
    });
  }

  // Call the fetchTeamData function on page load
  fetchTeamData();
});
