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
  <title>Events - PUP Bi�an Campus</title>
  <meta name="description" content="Campus events, schedules, and calendar for PUP Bi�an Campus." />
  <meta name="theme-color" content="#7a0019" />
  <link rel="stylesheet" href="../asset/css/site.css" />
  <link rel="stylesheet" href="../asset/css/events.css" />
  <script defer src="../asset/js/homepage.js"></script>
  <script defer src="../asset/js/chatbot.js"></script>
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
        <span class="c">Bi�an Campus</span>
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
          <span class="c">Bi�an Campus</span>
        </div>
      </div>
      <nav aria-label="Primary" class="menu" id="menu">
        <a href="../homepage.php">Home</a>
        <a href="./about.php">About</a>
        <a href="./programs.php">Academic Programs</a>
        <a href="./admission_guide.php">Admissions</a>
        <a href="./services.php">Student Services</a>
        <a class="is-active" href="./event.php">Events</a>
        <a href="./contact.php">Contact</a>
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
        <a class="is-active" href="./event.php">Events</a>
        <a href="./contact.php">Contact</a>
      </nav>
    </div>
    -->
  </header>

  <main id="content">
    <section class="hero">
      <div class="container hero-inner">
        <div>
          <h1>Campus <span class="accent">Events</span></h1>
          <p>Browse upcoming events, university activities, and academic calendars.</p>
          
        </div>
        <!--
        <aside class="hero-card" aria-label="Highlights">
          <div class="head">Highlights</div>
          <div class="list">
            <span class="pill"><span class="date">Oct</span> IT Week 2025</span>
            <span class="pill"><span class="date">Sep</span> Research Colloquium</span>
          </div>
        </aside>
        -->
      </div>
    </section>

    <section class="section" id="upcoming">
      <div class="container">
        <h2>Upcoming Events</h2>
        <div class="grid cols-3">
          <article class="card"><div class="body"><b>IT Week 2025</b><br><small class="muted">Oct 10–14 • Talks, exhibits, competitions</small><p><a href="#">View schedule</a></p></div></article>
          <article class="card"><div class="body"><b>Campus Clean‑up Drive</b><br><small class="muted">Oct 3 • Student Affairs</small><p><a href="#">Join activity</a></p></div></article>
          <article class="card"><div class="body"><b>Research Colloquium</b><br><small class="muted">Sep 30 • Library</small><p><a href="#">Program details</a></p></div></article>
        </div>
      </div>
    </section>

    <section class="section" id="calendar">
      <div class="container">
        <article class="card" aria-labelledby="calHead">
          <div class="toolbar">
            <h3 id="calHead">Events Calendar</h3>
            <span class="tag">Google Calendar</span>
          </div>
          <iframe class="calendar" title="Campus Calendar"
                  src="https://calendar.google.com/calendar/embed?src=en.philippines%23holiday%40group.v.calendar.google.com&ctz=Asia%2FManila"></iframe>
        </article>
      </div>
    </section>
  </main>

  <footer id="contact">
    <div class="container foot">
       <div>
          <p>Page Views: <?php echo $page_views; ?></p>
  <p>Total Visitors: <?php echo $total_visitors; ?></p>
        </div>        
      <div>
        <h4>About PUP Bi�an</h4>
        <p>PUP Bi�an Campus is part of the country's largest state university system, committed to accessible and excellent public higher education.</p>
      </div>
      <div>
        <h4>Contact</h4>
        <p>Sto. Tomas, Bi�an, Laguna<br/>Philippines 4024</p>
        <p>Email: <a href="mailto:info.binan@pup.edu.ph">info.binan@pup.edu.ph</a><br/>Phone: (xxx) xxx xxxx</p>
      </div>
      <div>
        <h4>Connect</h4>
        <p><a href="#">Facebook</a> � <a href="#">X</a> � <a href="#">YouTube</a></p>
        <p><a href="#">Feedback & FAQs</a></p>
      </div>
    </div>
    <div class="container sub">c <span id="year"></span> PUP Bi�an Campus. For demo/UI purposes only.</div>
  </footer>

  <button id="backTop" class="btn" aria-label="Back to top" title="Back to top"> Top</button>
</body>
</html>
