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
  <title>Academic Programs - PUP Bi&ntilde;an Campus</title>
  <meta name="description" content="Explore academic programs offered at PUP Bi&ntilde;an Campus. UI-only, static HTML/CSS/JS." />
  <meta name="theme-color" content="#7a0019" />
  <link rel="stylesheet" href="../asset/css/site.css" />
  <link rel="stylesheet" href="../asset/css/programs.css" />
  <script defer src="../asset/js/homepage.js"></script>
  <script defer src="../asset/js/chatbot.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const filter = document.getElementById('progFilter');
      if (!filter) return;
      const cards = Array.from(document.querySelectorAll('.prog-card'));
      filter.addEventListener('input', () => {
        const q = filter.value.trim().toLowerCase();
        cards.forEach(card => {
          const text = card.textContent.toLowerCase();
          card.style.display = text.includes(q) ? '' : 'none';
        });
      });
    });
  </script>
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
        <span class="c">Bi&ntilde;an Campus</span>
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
          <span class="c">Bi&ntilde;an Campus</span>
        </div>
      </div>

      <nav aria-label="Primary" class="menu" id="menu">
        <a href="../homepage.php">Home</a>
        <a href="./about.php">About</a>
        <a class="is-active" href="./programs.php">Academic Programs</a>
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
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="../homepage.php">Home</a></li>
        <li><span>Programs</span></li>
      </ol>
    </nav>

    <section class="hero">
      <div class="container hero-inner">
        <div>
          <h1>Academic <span class="accent">Programs</span></h1>
          <p>
            Explore our undergraduate offerings designed to deliver accessible, industry-aligned, and values-driven public education.
          </p>
        </div>
      </div>
    </section>

    <section class="section" id="offerings">
      <div class="container">
        <h2>Undergraduate Offerings</h2>
        <p class="muted">Select a program to see a focused overview, highlights, and potential career pathways.</p>
        <div class="grid cols-3" role="list">
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/BSIT.html">BS Information Technology (BSIT)</a></h3>
                <p>Design and maintain software, systems, and secure digital solutions for businesses and communities.</p>
                <div class="prog-badges">
                  <span class="badge">Software Dev</span>
                  <span class="badge">Systems</span>
                  <span class="badge">Cybersecurity</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="Students in the BSIT program collaborating on computers" />
              </figure>
            </div>
          </article>
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/BSBA.html">BS Business Administration (BSBA)</a></h3>
                <p>Build strategic, ethical, and entrepreneurial management skills for diverse organizational settings.</p>
                <div class="prog-badges">
                  <span class="badge">Management</span>
                  <span class="badge">Marketing</span>
                  <span class="badge">Finance</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="BSBA learners discussing a business plan" />
              </figure>
            </div>
          </article>
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/BSCPE.html">BS Computer Engineering (BSCpE)</a></h3>
                <p>Integrate hardware and software through electronics, embedded systems, and intelligent devices.</p>
                <div class="prog-badges">
                  <span class="badge">Electronics</span>
                  <span class="badge">Automation</span>
                  <span class="badge">IoT</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="BSCpE students testing hardware prototypes" />
              </figure>
            </div>
          </article>
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/BSIE.html">BS Industrial Engineering (BSIE)</a></h3>
                <p>Optimize processes, resources, and people-centered systems for manufacturing and service industries.</p>
                <div class="prog-badges">
                  <span class="badge">Operations</span>
                  <span class="badge">Quality</span>
                  <span class="badge">Analytics</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="BSIE majors evaluating a production workflow" />
              </figure>
            </div>
          </article>
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/BEED.html">Bachelor of Elementary Education (BEEd)</a></h3>
                <p>Prepare to facilitate holistic, inclusive learning experiences for elementary-level pupils.</p>
                <div class="prog-badges">
                  <span class="badge">Pedagogy</span>
                  <span class="badge">Child Dev</span>
                  <span class="badge">Fieldwork</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="BEEd preservice teachers working with children" />
              </figure>
            </div>
          </article>
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/BSEDEnglish.html">BSEd Major in English</a></h3>
                <p>Develop expertise in English language, literature, and communication for secondary education.</p>
                <div class="prog-badges">
                  <span class="badge">Language</span>
                  <span class="badge">Literature</span>
                  <span class="badge">Instruction</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="English education majors facilitating a discussion" />
              </figure>
            </div>
          </article>
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/BSEDSocialStudies.html">BSEd Major in Social Studies</a></h3>
                <p>Engage learners in history, culture, and civic life through inquiry-based social science education.</p>
                <div class="prog-badges">
                  <span class="badge">History</span>
                  <span class="badge">Civics</span>
                  <span class="badge">Community</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="Social Studies majors presenting community research" />
              </figure>
            </div>
          </article>
        </div>
        <h2>Diploma Offerings</h2>
        <p class="muted">Flexible programs that blend hands-on training with foundational theory for career-ready technologists.</p>
        <div class="grid cols-3" role="list">
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/DCET.html">Diploma in Computer Engineering Technology</a></h3>
                <p>Gain practical experience in electronics, embedded systems, and networked devices for modern industries.</p>
                <div class="prog-badges">
                  <span class="badge">Embedded</span>
                  <span class="badge">Automation</span>
                  <span class="badge">Lab Work</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="Computer engineering technology students in a hardware lab" />
              </figure>
            </div>
          </article>
          <article class="card prog-card" role="listitem">
            <div class="body">
              <div class="prog-copy">
                <h3><a href="../programsinfos/DIT.html">Diploma in Information Technology</a></h3>
                <p>Develop technical skills in programming, systems support, and digital solutions for emerging business needs.</p>
                <div class="prog-badges">
                  <span class="badge">Coding</span>
                  <span class="badge">IT Support</span>
                  <span class="badge">Projects</span>
                </div>
              </div>
              <figure class="prog-media">
                <img src="../images/pupbackrgound.jpg" alt="Information technology students collaborating on a project" />
              </figure>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="section" id="curricula">
      <div class="container split">
        <article class="card">
          <h3>Curriculum Guides</h3>
          <div class="body">
            <p>Provide official PDFs or links to the latest curricula per program.</p>
            <ul>
              <li><a href="#">BSIT Curriculum Guide (PDF)</a></li>
              <li><a href="#">BSBA Curriculum Guide (PDF)</a></li>
              <li><a href="#">BSCpE Curriculum Guide (PDF)</a></li>
              <li><a href="#">BSIE Curriculum Guide (PDF)</a></li>
              <li><a href="#">BEEd Curriculum Guide (PDF)</a></li>
              <li><a href="#">BSEd-English Curriculum Guide (PDF)</a></li>
              <li><a href="#">BSEd-Social Studies Curriculum Guide (PDF)</a></li>
            </ul>
          </div>
        </article>

        <article class="card">
          <div class="toolbar">
            <h3>Why Choose PUP Bi&ntilde;an</h3>
            <span class="tag">Highlights</span>
          </div>
          <div class="body">
            <div class="annos">
              <div class="anno">
                <strong>Accessible public education</strong>
                <small>Values-driven instruction</small>
              </div>
              <div class="anno">
                <strong>Industry-aligned training</strong>
                <small>Projects, internships, and partnerships</small>
              </div>
              <div class="anno">
                <strong>Community engagement</strong>
                <small>Extension and service learning</small>
              </div>
            </div>
          </div>
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
        <h4>About PUP Bi&ntilde;an</h4>
        <p>PUP Bi&ntilde;an Campus is part of the country's largest state university system, committed to accessible and excellent public higher education.</p>
      </div>
      <div>
        <h4>Contact</h4>
        <p>Sto. Tomas, Bi&ntilde;an, Laguna<br/>Philippines 4024</p>
        <p>Email: <a href="mailto:info.binan@pup.edu.ph">info.binan@pup.edu.ph</a><br/>Phone: (xxx) xxx xxxx</p>
      </div>
      <div>
        <h4>Connect</h4>
        <p><a href="#">Facebook</a> &bull; <a href="#">X</a> &bull; <a href="#">YouTube</a></p>
        <p><a href="#">Feedback &amp; FAQs</a></p>
      </div>
    </div>
    <div class="container sub">&copy; <span id="year"></span> PUP Bi&ntilde;an Campus. For demo/UI purposes only.</div>
  </footer>

  <button id="backTop" class="btn" aria-label="Back to top" title="Back to top">&#8679; Top</button>
</body>
</html>
