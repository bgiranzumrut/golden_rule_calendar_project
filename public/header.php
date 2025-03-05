<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container">
        <a class="navbar-brand d-flex" href="./index.php">
            <img src="/golden_rule_calendar_project/uploads/logo.png" alt="Golden Rule Calendar Logo" class="navbar-logo me-2">
            <span class="brand-title">
                <span class="highlight">Golden Rule Seniors</span> Resource Centre
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="/golden_rule_calendar_project/index.php">Home</a></li>
<li class="nav-item"><a class="nav-link" href="/golden_rule_calendar_project/public/about.php">About</a></li>
<li class="nav-item"><a class="nav-link" href="/golden_rule_calendar_project/public/gallery.php">Gallery</a></li>
<li class="nav-item"><a class="nav-link btn btn-danger text-white px-3" href="/golden_rule_calendar_project//public/login.php">Admin Login</a></li>

            </ul>
        </div>
    </div>
</nav>

<style>
/* ABOUT PAGE STYLES */
.about-container {
    max-width: 1000px;
    margin: auto;
    padding: 20px;
}

/* Info Section */
.info {
    text-align: center;
    background-color: var(--primary-cream);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.info-text p {
    font-size: 1.2rem;
    line-height: 1.5;
    color: var(--text-dark);
}

/* Contact Section */
.contact {
    text-align: center;
    margin-top: 20px;
}

.contact h2 {
    color: var(--primary-blue);
    font-size: 1.5rem;
}

.contact p {
    font-size: 1rem;
    margin: 5px 0;
}

.contact a {
    color: var(--primary-red);
    font-weight: bold;
    text-decoration: none;
}

.contact a:hover {
    text-decoration: underline;
    color: var(--primary-blue);
}

/* Location Map */
section div img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Team Section */
.team {
    margin-top: 20px;
    background-color: var(--bg-light);
    padding: 15px;
    border-radius: 8px;
}

.team h2 {
    text-align: center;
    color: var(--primary-blue);
}

.team ul {
    list-style: none;
    padding: 0;
}

.team li {
    background: var(--white);
    padding: 10px;
    margin: 10px 0;
    border-left: 5px solid var(--primary-red);
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .info-text p {
        font-size: 1rem;
    }

    .contact h2 {
        font-size: 1.3rem;
    }

    .team h2 {
        font-size: 1.3rem;
    }
}
</style>