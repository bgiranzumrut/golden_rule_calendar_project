<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container">
        <a class="navbar-brand d-flex" href="./index.php">
            <img src="./uploads/logo.png" alt="Golden Rule Calendar Logo" class="navbar-logo me-2">
            <span class="brand-title">
                <span class="highlight">Golden Rule Seniors</span> Resource Centre
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="./index.php">Home</a></li>
<li class="nav-item"><a class="nav-link" href="./public/about.php">About</a></li>
<li class="nav-item"><a class="nav-link" href="./public/gallery.php">Gallery</a></li>
<li class="nav-item">
    <a class="nav-link" href="./public/archive.php">Archive</a>
</li>
<li class="nav-item"><a class="nav-link btn btn-danger text-white px-3" href="./public/login.php">Admin</a></li>

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

</style>