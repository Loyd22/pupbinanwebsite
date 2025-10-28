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
  <title>Student Services - PUP Bi�an Campus</title>
  <meta name="description" content="Student Services at PUP Bi�an Campus: registrar, scholarships, guidance, library, and more." />
  <meta name="theme-color" content="#7a0019" />
  <link rel="stylesheet" href="../asset/css/site.css" />
  <link rel="stylesheet" href="../asset/css/services.css" />
  <script defer src="../asset/js/homepage.js"></script>
  <script defer src="../asset/js/chatbot.js"></script>
  <style>
    .svc-icons { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.5rem }
    .svc-icons .badge { font-size:.8rem; padding:.25rem .5rem; border:1px solid var(--border); border-radius:.5rem; background:#f9fafb }
  </style>
</head>
<body>

  <!-- Top ribbon -->
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

  <!-- Header / Nav -->
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
        <a class="is-active" href="./services.php">Student Services</a>
        <a href="./event.php">Events</a>
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
        <a class="is-active" href="./services.php">Student Services</a>
        <a href="./event.php">Events</a>
        <a href="./contact.php">Contact</a>
      </nav>
    </div>
    -->
  </header>

  <main id="content">
    <!-- HERO -->
    <section class="hero">
      <div class="container hero-inner">
        <div>
          <h1>Student <span class="accent">Services</span></h1>
          <p>Access registrar, scholarships, guidance and counseling, library resources, and more.</p>
          
        </div>
        <!--
        <aside class="hero-card" aria-label="Quick links">
          <div class="head">Popular Requests</div>
          <div class="list">
            <span class="pill"><span class="date">Form</span> TOR/Records Request</span>
            <span class="pill"><span class="date">ID</span> ID Validation/Reissue</span>
            <span class="pill"><span class="date">Aid</span> Scholarship Application</span>
          </div>
        </aside>
        -->
      </div>
    </section>

    <!-- OFFICES & SERVICES -->
    <section class="section" id="offices">
      <div class="container">
        <h2>Campus Offices</h2>
        <p class="muted">Key student-facing services. Replace contact details with your official info.</p>
        <div class="grid cols-3" role="list">
          <article class="card" role="listitem">
            <div class="body">
              <h3>Registrar</h3>
              <p>Enrollment, records, certifications, TOR, ID validation.</p>
              <div class="svc-icons">
                <span class="badge">Enrollment</span>
                <span class="badge">TOR</span>
                <span class="badge">Certification</span>
              </div>
            </div>
          </article>
          <article class="card" role="listitem">
            <div class="body">
              <h3>Scholarships & Grants</h3>
              <p>Financial assistance programs and application support.</p>
              <div class="svc-icons">
                <span class="badge">Scholarship</span>
                <span class="badge">Grants</span>
                <span class="badge">Aid</span>
              </div>
            </div>
          </article>
          <article class="card" role="listitem">
            <div class="body">
              <h3>Guidance & Counseling</h3>
              <p>Counseling, career guidance, wellness initiatives.</p>
              <div class="svc-icons">
                <span class="badge">Counseling</span>
                <span class="badge">Career</span>
                <span class="badge">Wellness</span>
              </div>
            </div>
          </article>
          <article class="card" role="listitem">
            <div class="body">
              <h3>Library</h3>
              <p>Print and digital resources, research support, study spaces.</p>
              <div class="svc-icons">
                <span class="badge">Books</span>
                <span class="badge">eResources</span>
                <span class="badge">Research</span>
              </div>
            </div>
          </article>
          <article class="card" role="listitem">
            <div class="body">
              <h3>Student Affairs</h3>
              <p>Student orgs, activities, discipline, and campus life.</p>
              <div class="svc-icons">
                <span class="badge">Orgs</span>
                <span class="badge">Activities</span>
                <span class="badge">Discipline</span>
              </div>
            </div>
          </article>
          <article class="card" role="listitem">
            <div class="body">
              <h3>IT Services</h3>
              <p>Accounts, portals, Wi‑Fi access, and basic troubleshooting.</p>
              <div class="svc-icons">
                <span class="badge">Accounts</span>
                <span class="badge">Portals</span>
                <span class="badge">Wi‑Fi</span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- HOW-TOS -->
    <section class="section" id="howtos">
      <div class="container split">
        <article class="card">
          <h3>Common Requests</h3>
          <div class="body">
            <div class="annos">
              <div class="anno"><strong>How to request a TOR</strong><small>Fill out the records form, pay fees (if any), and await email.</small></div>
              <div class="anno"><strong>How to validate your ID</strong><small>Visit Registrar with your enrollment proof and ID photo.</small></div>
              <div class="anno"><strong>How to apply for a scholarship</strong><small>Check open calls, prepare documents, and submit via office email.</small></div>
            </div>
          </div>
        </article>
        <article class="card">
          <div class="toolbar">
            <h3>Downloads</h3>
            <span class="tag">Forms</span>
          </div>
          <div class="body">
            <ul>
              <li><a href="#">Student Records Request Form (PDF)</a></li>
              <li><a href="#">Scholarship Application Form (PDF)</a></li>
              <li><a href="#">Guidance Appointment Slip (PDF)</a></li>
            </ul>
          </div>
        </article>
      </div>
    </section>

    <!-- FAQs -->
    <section class="section" id="faqs">
      <div class="container">
        <h2>FAQs</h2>
        <div class="grid cols-2">
          <article class="card"><div class="body"><b>Where can I follow announcements?</b><br><small class="muted">See Announcements page or the campus FB page.</small></div></article>
          <article class="card"><div class="body"><b>How do I get a certificate of enrollment?</b><br><small class="muted">Request via Registrar with your current registration.</small></div></article>
          <article class="card"><div class="body"><b>Is there a student handbook?</b><br><small class="muted">Yes — check Downloads or Student Affairs office.</small></div></article>
          <article class="card"><div class="body"><b>How do I reset my portal password?</b><br><small class="muted">Contact IT Services with a valid ID and email.</small></div></article>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
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
