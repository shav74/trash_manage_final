<?php require('inc/connection.php'); ?>
<nav class="nav">
  <i class="uil uil-bars navOpenBtn"></i>
  <a href="homepage.php" class="logo">CMC</a>

  <ul class="nav-links">
    <i class="uil uil-times navCloseBtn"></i>
    <li><a class='navl' href="homepage.php">Home</a></li>
    <li><a class='navl' href='map.php'>Dump Locations</a></li>

    <?php

    if (isset($_SESSION['user_type'])) {
      if ($_SESSION['user_type'] == 2) {
        echo "<li><a href='report-list.php'>Report List</a></li>";
        echo "<li><a href='cleaned-history.php'>Cleaned History</a></li>";
        echo "<li><a href='in-progress.php'>In Progress</a></li>";
      }
      if ($_SESSION['user_type'] == 1) {
        echo "<li><a href='cleaning-list.php'>Cleaning List</a></li>";
        echo "<li><a href='cleaned-history.php'>Cleaned History</a></li>";
      }
      if ($_SESSION['user_type'] == 0) {
        echo "<li><a href='location.php'>New Report</a></li>";
        echo "<li><a href='my-reports.php'>My Reports</a></li>";
        echo "<li><a href='contact.php'>Contact Us</a></li>";
      }
      if ($_SESSION['user_type'] == 3) {
        echo "<li><a href='write-article.php'>Write Article</a></li>";
        echo "<li><a href='users.php'>UMS</a></li>";
        echo "<li><a href='manage-articles.php'>Manage Articles</a></li>";
      }
    } else {
      echo "<li><a href='index.php'>Log In</a></li>";
      echo "<li><a href='register.php'>Register</a></li>";
    }

    ?>

    <?php if (isset($_SESSION['user_id'])) { ?>

      <li><a class='navl' href="profile.php?user_id=<?= $_SESSION['user_id'] ?>">Profile</a></li>
      <?php
    } ?>
  </ul>

  <i class="" id="searchIcon"></i>
  <div class="search-box">
    <i class="uil uil-search search-icon"></i>
    <input type="text" placeholder="Search here..." />
  </div>
</nav>