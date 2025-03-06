<?php
// Use the full server path to include the config file
include_once __DIR__ . '/../config/config.php'; // Include the config file to use getBasePath function
?>
<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container">
        <a class="navbar-brand d-flex" href="<?php echo getBasePath(); ?>index.php">
            <img src="<?php echo getBasePath(); ?>uploads/logo.png" alt="Golden Rule Calendar Logo" class="navbar-logo me-2">
            <span class="brand-title">
                <span class="highlight">Golden Rule Seniors</span> Resource Centre
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="<?php echo getBasePath(); ?>index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo getBasePath(); ?>public/about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo getBasePath(); ?>public/gallery.php">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo getBasePath(); ?>public/archive.php">Archive</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white px-3" href="<?php echo getBasePath(); ?>public/login.php">Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

 <style>
.custom-navbar {
  background-color: var(--primary-cream);
  border-bottom: 3px solid var(--primary-red);
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
  padding: 12px 0;
}

.navbar-brand {
  display: flex;
  align-items: center;
}

.brand-title {
  font-size: 1.6rem;
  font-weight: 700;
  color: var(--primary-blue);
}

.highlight {
  color: var(--primary-red);
  font-weight: 00; /* Make it even bolder */
}

.navbar-logo {
  width: 100px; /* Adjust for smaller screens */
  height: 100px;
  border-radius: 50%;
}

.navbar-nav {
  display: flex;
  align-items: center;
  gap: 15px;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link:focus {
  background-color: var(--hover-blue);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
  transform: scale(1.05);
  color: var(--white);
}

/* Admin Login Link Styling */

.navbar-nav .nav-link {
  background-color: var(--primary-red);
  color: var(--white);
  font-size: 1.1rem;
  font-weight: bold;
  padding: 8px 16px;
  border-radius: 8px;
  transition: background 0.3s ease, box-shadow 0.2s ease;
}

.nav-link {
  font-size: 1.1rem;
  font-weight: bold;
  padding: 15px 15px;
  transition: all 0.3s ease-in-out;
  width: 130px;
}

.navbar-nav .nav-link.active {
  background-color: var(--primary-blue);
}
.navbar-nav .nav-link:hover {
  background-color: var(--hover-blue);
  transform: scale(1.05);
  background-color: var(--hover-blue);
}
.navbar-nav {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap; /* Ensures items wrap properly on small screens */
}

.navbar-nav a {
  text-align: center;
}

.navbar-toggler:focus {
  outline: none;
  box-shadow: none;
}

.navbar-toggler {
  border: none;
  font-size: 1.5rem;
}

/* Add these new styles to ensure nav items stay in one line */
@media (min-width: 992px) {
  .navbar-nav {
    flex-direction: row;
    flex-wrap: nowrap;
    white-space: nowrap;
  }
  
  .nav-item {
    display: inline-block;
  }
}

/* Ensure the navbar collapses at an appropriate breakpoint */
@media (max-width: 991px) {
  .navbar-collapse {
    position: absolute;
    top: 100%;
    right: 0;
    left: 0;
    background-color: var(--primary-cream);
    z-index: 1000;
    padding: 10px;
    border-bottom: 3px solid var(--primary-red);
  }
}
</style>

<!-- Add Bootstrap JavaScript to ensure hamburger menu works on all pages -->
<script>
  // Wait for the document to be fully loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Get the navbar toggler button
    var navbarToggler = document.querySelector('.navbar-toggler');
    // Get the navbar collapse element
    var navbarCollapse = document.querySelector('.navbar-collapse');
    
    // Add click event listener to the toggler button
    if (navbarToggler) {
      navbarToggler.addEventListener('click', function() {
        // Toggle the 'show' class on the navbar collapse element
        if (navbarCollapse) {
          navbarCollapse.classList.toggle('show');
        }
      });
    }
    
    // Close the menu when a nav link is clicked (optional, for better UX)
    var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
          navbarCollapse.classList.remove('show');
        }
      });
    });
  });
</script>