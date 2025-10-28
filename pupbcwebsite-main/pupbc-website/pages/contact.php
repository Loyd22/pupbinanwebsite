<?php
include "../DATAANALYTICS/db.php";
require_once __DIR__ . '/../DATAANALYTICS/page_visits.php';

$page_name = basename(__FILE__, '.php');
$ip = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

record_page_visit($conn, $page_name, $ip, $today);
$page_views = get_page_visit_count($conn, $page_name);

$total_visitors = 0;
if ($result = $conn->query("SELECT COUNT(*) AS total FROM visitors")) {
  $row = $result->fetch_assoc();
  $total_visitors = (int)($row['total'] ?? 0);
  $result->free();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us - PUP Binan Campus</title>
  <meta name="description" content="Contact information, location map, and inquiry form for PUP Binan Campus." />
  <meta name="theme-color" content="#7a0019" />
  <link rel="stylesheet" href="../asset/css/site.css" />
  <link rel="stylesheet" href="../asset/css/contact.css" />
  <script defer src="../asset/js/homepage.js"></script>
  <script defer src="../asset/js/chatbot.js"></script>
  <style>
    .contact-grid { display:grid; grid-template-columns: 1fr 1fr; gap:1rem }
    @media (max-width:920px){ .contact-grid{ grid-template-columns:1fr } }
    .map { aspect-ratio: 16/10; width:100%; border:0; border-radius:1rem }
  </style>
</head>
<body>

  <div class="topbar" role="banner">
    <div class="container topbar-inner">
      <div class="seal" aria-hidden="true">
        <a href="../homepage.php">
          <img src="../images/PUPLogo.png" alt="PUP Logo" />
        </a>
      </div>
      <div class="brand" aria-label="Campus name">
        <span class="u">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</span>
        <span class="c">Binan Campus</span>
      </div>
    </div>
  </div>

  <header>
    <div class="container nav">
      <div class="brand-nav">
        <div class="seal" aria-hidden="true">
          <a href="../homepage.php">
            <img src="../images/PUPLogo.png" alt="PUP Logo" />
          </a>
        </div>
        <div class="brand" aria-label="Campus name">
          <span class="u">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</span>
          <span class="c">Bi?an Campus</span>
        </div>
      </div>
      <nav aria-label="Primary" class="menu" id="menu">
        <a href="../homepage.php">Home</a>
        <a href="./about.php">About</a>
        <a href="./programs.php">Academic Programs</a>
        <a href="./admission_guide.php">Admissions</a>
        <a href="./services.php">Student Services</a>
        <a href="./event.php">Events</a>
        <a class="is-active" href="./contact.php">Contact</a>
      </nav>
      <form class="search-form" action="../search.php" method="get">
        <input type="text" name="q" placeholder="Search..." aria-label="Search">
      </form>
    </div>
    <!--
    <div class="mobile-panel" id="mobilePanel" aria-hidden="true">
      <nav class="mobile-menu" aria-label="Mobile">
        <a href="../homepage.php">Home</a>
        <a href="./about.php">About</a>
        <a href="./programs.php">Academic Programs</a>
        <a href="./admission_guide.php">Admissions</a>
        <a href="./services.php">Student Services</a>
        <a href="./event.php">Events</a>
        <a class="is-active" href="./contact.php">Contact</a>
      </nav>
    </div>
    -->
  </header>

  <main id="content">
    <section class="hero">
      <div class="container hero-inner">
        <div>
          <h1>Contact <span class="accent">Us</span></h1>
          <p>Reach the campus by email, phone, or visit our location.</p>
          
        </div>
        <!--
        <aside class="hero-card" aria-label="Office hours">
          <div class="head">Office Hours</div>
          <div class="list">
            <span class="pill"><span class="date">Mon-Fri</span> 8:00 AM - 5:00 PM</span>
            <span class="pill"><span class="date">Sat</span> Closed</span>
          </div>
        </aside>
        -->
      </div>
    </section>

    <section class="section" id="details">
      <div class="container contact-grid">
        <article class="card">
          <div class="body">
            <h2>Campus Details</h2>
            <p><b>Address:</b><br/>Sto. Tomas, Binan, Laguna, Philippines 4024</p>
            <p><b>Email:</b> <a href="mailto:info.binan@pup.edu.ph">info.binan@pup.edu.ph</a><br/>
               <b>Phone:</b> (xxx) xxx xxxx</p>
            <p><b>Socials:</b> <a href="#">Facebook</a> | <a href="#">X</a> | <a href="#">YouTube</a></p>
          </div>
        </article>
        <article class="card">
          <div class="toolbar">
            <h3>Location Map</h3>
            <span class="tag">Embed</span>
          </div>
          <iframe class="map" title="Campus Map" src="https://www.openstreetmap.org/export/embed.html?bbox=121.0%2C14.3%2C121.2%2C14.5&amp;layer=mapnik"></iframe>
        </article>
      </div>
    </section>

    <!-- Inquiry form removed per request -->
  </main>

  <footer id="contact">
    <div class="container foot">
       <div>
          <p>Page Views: <?php echo $page_views; ?></p>
  <p>Total Visitors: <?php echo $total_visitors; ?></p>
        </div>        
      <div>
        <h4>About PUP Binan</h4>
        <p>PUP Binan Campus is part of the country's largest state university system, committed to accessible and excellent public higher education.</p>
      </div>
      <div>
        <h4>Contact</h4>
        <p>Sto. Tomas, Binan, Laguna<br/>Philippines 4024</p>
        <p>Email: <a href="mailto:info.binan@pup.edu.ph">info.binan@pup.edu.ph</a><br/>Phone: (xxx) xxx xxxx</p>
      </div>
      <div>
        <h4>Connect</h4>
        <p><a href="#">Facebook</a> | <a href="#">X</a> | <a href="#">YouTube</a></p>
        <p><a href="#">Feedback & FAQs</a></p>
      </div>
    </div>
    <div class="container sub">c <span id="year"></span> PUP Binan Campus. For demo/UI purposes only.</div>
  </footer>

  <button id="backTop" class="btn" aria-label="Back to top" title="Back to top">Top</button>
</body>
</html>
