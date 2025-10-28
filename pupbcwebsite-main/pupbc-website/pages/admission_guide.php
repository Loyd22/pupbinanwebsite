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
  <title>Admission Guide - PUP Bi�an Campus</title>
  <meta name="description" content="Step-by-step admission guide on how to apply to PUP Bi�an Campus. UI-only, static HTML/CSS/JS." />
  <meta name="theme-color" content="#7a0019" />
  <link rel="stylesheet" href="../asset/css/site.css" />
  <link rel="stylesheet" href="../asset/css/admissions.css" />
  <script defer src="../asset/js/homepage.js"></script>
  <script defer src="../asset/js/chatbot.js"></script>
  <style>
    .step-list{display:grid;gap:.65rem}
    .step{display:grid;gap:.25rem;padding:.9rem 1rem;border-left:4px solid var(--maroon);background:#fff;border-radius:.25rem}
    .num{font-weight:800;color:var(--maroon);font-size:.85rem}
    .callout{padding:1rem;border:1px dashed var(--border);border-radius:.9rem;background:#fff}
  </style>
</head>
<body>

  <!-- Top ribbon (matches other pages) -->
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

  <!-- Header / Nav (same structure) -->
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
        <a class="is-active" href="./admission_guide.php">Admissions</a>
        <a href="./services.php">Student Services</a>
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
        <a class="is-active" href="./admission_guide.php">Admissions</a>
        <a href="./services.php">Student Services</a>
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
          <h1>Admission <span class="accent">Guide</span></h1>
          <p>
            Follow these steps to apply to the Polytechnic University of the Philippines — Bi�an Campus.
            This page summarizes the process, requirements, and key dates.
          </p>
          
        </div>

        <!--
        <aside class="hero-card" aria-label="Application tips">
          <div class="head">Before You Start</div>
          <div class="list">
            <span class="pill"><span class="date">Tip</span> Prepare clear scans of your documents.</span>
            <span class="pill"><span class="date">Tip</span> Use a personal, active email address.</span>
            <span class="pill"><span class="date">Tip</span> Check program-specific requirements.</span>
          </div>
        </aside>
        -->
      </div>
    </section>

    <!-- STEPS -->
    <section class="section" id="steps">
      <div class="container split">
        <article class="card">
          <h3>How to Apply</h3>
          <div class="body step-list">
            <div class="step"><div class="num">Step 1</div><div><b>Create/Log in to your PUP iApply account</b> and fill out your personal information.</div></div>
            <div class="step"><div class="num">Step 2</div><div><b>Select campus and program</b> preferences for PUP Bi�an and review eligibility.</div></div>
            <div class="step"><div class="num">Step 3</div><div><b>Upload requirements</b> (see list below) in clear, readable format.</div></div>
            <div class="step"><div class="num">Step 4</div><div><b>Submit application</b> and monitor your email/iApply portal for updates.</div></div>
            <div class="step"><div class="num">Step 5</div><div><b>Take/Complete assessment</b> if applicable and await results.</div></div>
          </div>
        </article>

        <article class="card">
          <div class="toolbar">
            <h3>Helpful Links</h3>
            <span class="tag">Online</span>
          </div>
          <div class="body">
            <div class="callout">
              Replace the links below with your official campus application URLs.
            </div>
            <ul>
              <li><a href="#">PUP iApply Portal</a></li>
              <li><a href="#">Admission Announcements</a></li>
              <li><a href="#">Contact Admissions</a></li>
            </ul>
          </div>
        </article>
      </div>
    </section>

    <!-- REQUIREMENTS -->
    <section class="section" id="requirements">
      <div class="container grid cols-2">
        <article class="card">
          <div class="body">
            <h2>General Requirements</h2>
            <ul>
              <li>Completed application form (via iApply)</li>
              <li>PSA Birth Certificate</li>
              <li>Recent 2x2 ID photo (white background)</li>
              <li>Valid ID (student/school ID or government ID)</li>
              <li>Good Moral Certificate</li>
              <li>Form 138/Report Card (for incoming freshmen) or TOR/Grades (for transferees)</li>
            </ul>
          </div>
        </article>

        <article class="card">
          <div class="body">
            <h2>Program-Specific Documents</h2>
            <p class="muted">Some programs may require additional documents or portfolio items.</p>
            <ul>
              <li>Track-specific requirements (if any)</li>
              <li>Medical certificate (if required)</li>
              <li>Certification for honors/scholarships (optional)</li>
            </ul>
          </div>
        </article>
      </div>
    </section>

    <!-- DATES / TIMELINE -->
    <section class="section" id="dates">
      <div class="container split">
        <article class="card">
          <h3>Key Dates (Sample)</h3>
          <div class="body">
            <div class="annos">
              <div class="anno"><strong>Application opens</strong><small>January</small></div>
              <div class="anno"><strong>Application deadline</strong><small>March</small></div>
              <div class="anno"><strong>Assessment/Screening</strong><small>April</small></div>
              <div class="anno"><strong>Results release</strong><small>May</small></div>
              <div class="anno"><strong>Enrollment Period</strong><small>June–July</small></div>
            </div>
          </div>
        </article>

        <article class="card">
          <div class="toolbar">
            <h3>After You’re Admitted</h3>
            <span class="tag">Next steps</span>
          </div>
          <div class="body">
            <ul>
              <li>Confirm your slot within the stated period</li>
              <li>Prepare enrollment requirements and payments (if any)</li>
              <li>Attend orientation and claim your student ID</li>
            </ul>
          </div>
        </article>
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
