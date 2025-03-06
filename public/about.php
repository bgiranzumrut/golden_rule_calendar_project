<?php
session_start();
include("header.php"); // Include existing header
?>

<main class="about-container">
    <section class="info">
        <div class="info-text">
            <p>
                Golden Rule Seniors Resource Centre is a non-profit organization committed to enhancing the quality of life for seniors in the River-Osborne community.
                <br>
                We firmly believe that a strong sense of community is essential for a better society.
            </p>
        </div>
    </section>

    <section class="contact">
        <h2>Contact Information</h2>
        <p><strong>Address:</strong> 625 Osborne St, Winnipeg, MB, R3L2B3</p>
        <p><strong>Phone:</strong> (204) 306-1114</p>
        <p><strong>Email:</strong> <a href="mailto:goldenrule@swsrc.ca">goldenrule@swsrc.ca</a></p>
    </section>

    <section class="location-map">
    <h2>Location Map</h2>
    <div>
        <a href="https://www.google.com/maps?q=625+Osborne+St+Winnipeg+MB+R3L2B3" target="_blank">
            <img src="map/map.png" alt="Institute Map">
        </a>
    </div>
</section>


    <section class="team">
        <h2>Meet Our Team</h2>
        <ul>
            <li>
                <p><strong>Joanne van Dyck, M.Sc.<br>Interim Seniors Resource Coordinator</strong></p>
                <p>Office: (204) 478-6169 | Cell: (431) 866-6776</p>
                <p>Email: <a href="mailto:resources@swsrc.ca">resources@swsrc.ca</a></p>
            </li>
            <li>
                <p><strong>Roman Shukh<br>Program Facilitator</strong></p>
                <p>Office: (204) 306-1114 | Cell: (204) 898-6653</p>
                <p>Email: <a href="mailto:goldenruleprograms@swsrc.ca">goldenruleprograms@swsrc.ca</a></p>
            </li>
        </ul>
    </section>
</main>

<?php include("footer.php"); // Include existing footer ?>

<style>
/* ============================
   GLOBAL STYLES (BASE SETTINGS)
   ============================ */
   :root {
    --primary-red: rgb(203, 68, 47);
    --primary-blue: rgb(64, 114, 151);
    --primary-cream: rgb(245, 240, 230);
    --text-dark: rgb(33, 37, 41);
    --text-muted: rgb(87, 87, 87);
    --border-color: rgb(200, 200, 200);
    --hover-blue: rgb(49, 88, 116);
    --bg-light: rgb(248, 249, 250);
    --white: rgb(255, 255, 255);
}

/* Base styles for body */
body {
    font-family: Arial, sans-serif;
    background-color: var(--primary-cream);
    color: var(--text-dark);
    font-size: 18px;
    margin: 0;
    padding: 0;
}

/* ============================
   MAIN CONTAINER
   ============================ */
.about-container {
    max-width: 900px; /* Keeps content aligned */
    margin: auto;
    padding: 20px;
}

/* ============================
   INFO SECTION
   ============================ */
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

/* ============================
   CONTACT SECTION
   ============================ */
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

/* ============================
   LOCATION MAP SECTION
   ============================ */
   .location-map, .team {
    max-width: 1000px; /* Ensures both sections are the same width */
    margin: 20px auto; /* Centers them properly */
    text-align: center;
}

.location-map div {
    width: 100%;
    height: auto;
    overflow: hidden;
    border-radius: 8px;
}

.location-map a img {
    display: block; /* Ensures proper rendering */
    width: 100% !important; /* Forces image to match the container */
    height: auto; /* Keeps aspect ratio */
    object-fit: cover; /* Ensures image fills the space */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}



/* ============================
   MEET OUR TEAM SECTION
   ============================ */
.team {
    max-width: 1000px; /* Same width as map */
    margin: 20px auto; /* Centered */
    padding: 20px;
    background-color: var(--bg-light);
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.team h2 {
    text-align: center;
    color: var(--primary-blue);
    font-size: 1.5rem;
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

/* ============================
   RESPONSIVE DESIGN
   ============================ */
@media (max-width: 1024px) {
    .about-container, .location-map, .team {
        max-width: 90%; /* Adjust width for tablets */
    }
}

@media (max-width: 768px) {
    /* Adjust font sizes and spacing for mobile */
    .info-text p {
        font-size: 1rem;
    }

    .contact h2, .team h2, .location-map h2 {
        font-size: 1.3rem;
    }

    .contact p {
        font-size: 0.9rem;
    }

    .team li {
        padding: 8px;
        font-size: 0.95rem;
    }
}

@media (max-width: 480px) {
    /* Stack elements for smaller screens */
    .about-container, .location-map, .team {
        max-width: 95%;
        padding: 15px;
    }

    .contact p, .team li {
        font-size: 0.85rem;
    }

    .location-map img {
        border-radius: 5px; /* Reduce rounding */
    }
}


</style>
