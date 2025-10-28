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
  <title>FAQs - PUP Biñan Campus</title>
  <meta name="description" content="Frequently Asked Questions for PUP Biñan Campus." />
  <meta name="theme-color" content="#7a0019" />
  <link rel="stylesheet" href="../asset/css/site.css" />
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
        <span class="c">Biñan Campus</span>
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
        <a href="./event.php">Events</a>
        <a href="./contact.php">Contact</a>
      </nav>
      <form class="search-form" action="../search.php" method="get">
        <input type="text" name="q" placeholder="Search..." aria-label="Search">
      </form>
    </div>
  </header>

  <main id="content">
    <section class="hero">
      <div class="container hero-inner">
        <div>
          <h1>Frequently Asked <span class="accent">Questions</span></h1>
          <p>Find quick answers or use the chat widget to ask a question.</p>
        </div>
      </div>
    </section>

    <section class="section" data-faq>
      <div class="container">
        <h2>FAQs</h2>
        <div class="grid cols-3">
          <article class="card faq-item">
            <div class="body">
              <div class="q"><b>How do I apply for admission?</b></div>
              <div class="a">Visit the Admissions page for the step-by-step guide and requirements, then submit through the official iApply portal when available.</div>
            </div>
          </article>
          <article class="card faq-item">
            <div class="body">
              <div class="q"><b>What are the general admission requirements?</b></div>
              <div class="a">Typically: Form 138/Report Card, PSA Birth Certificate, 2x2 photo, and valid ID. Some programs may have additional requirements.</div>
            </div>
          </article>
          <article class="card faq-item">
            <div class="body">
              <div class="q"><b>How can I request my TOR or certificates?</b></div>
              <div class="a">Requests are handled by the Registrar. Check the Student Services page for forms and processing timelines.</div>
            </div>
          </article>
          <article class="card faq-item">
            <div class="body">
              <div class="q"><b>Are scholarships available?</b></div>
              <div class="a">Yes. See Student Services & Scholarships for application periods and eligibility.</div>
            </div>
          </article>
          <article class="card faq-item">
            <div class="body">
              <div class="q"><b>Where can I see upcoming events?</b></div>
              <div class="a">Refer to the Events page and embedded calendar for schedules and announcements.</div>
            </div>
          </article>
        </div>
        <p class="muted" style="margin-top:.75rem">Add, edit, or remove Q&As above — the chatbot reads from these items automatically.</p>
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
        <h4>About PUP Biñan</h4>
        <p>PUP Biñan Campus is part of the country's largest state university system, committed to accessible and excellent public higher education.</p>
      </div>
      <div>
        <h4>Contact</h4>
        <p>Sto. Tomas, Biñan, Laguna<br/>Philippines 4024</p>
        <p>Email: <a href="mailto:info.binan@pup.edu.ph">info.binan@pup.edu.ph</a><br/>Phone: (xxx) xxx xxxx</p>
      </div>
      <div>
        <h4>Connect</h4>
        <p><a href="#">Facebook</a> · <a href="#">X</a> · <a href="#">YouTube</a></p>
        <p><a href="#">Feedback & FAQs</a></p>
      </div>
    </div>
    <div class="container sub">© <span id="year"></span> PUP Biñan Campus. For demo/UI purposes only.</div>
  </footer>

  <button id="backTop" class="btn" aria-label="Back to top" title="Back to top">↑ Top</button>
</body>
</html>
