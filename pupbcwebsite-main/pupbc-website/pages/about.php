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
  <title>About — PUP Biñan Campus</title>
  <meta name="description"
    content="About PUP Biñan Campus — history, vision, mission, values, and leadership." />
  <meta name="theme-color" content="#7a0019" /> <!-- note: we're inside /pages so use ../ -->
  <link rel="stylesheet" href="../asset/css/site.css" />
  <link rel="stylesheet" href="../asset/css/about.css" />
  <script defer src="../asset/js/homepage.js"></script>
  <script defer src="../asset/js/chatbot.js"></script>
  <style>
    /* Local styles for About page selector */
    .about-select-wrap {
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .about-select {
      padding: .55rem .7rem;
      border: 1px solid var(--border);
      border-radius: .65rem;
      font: inherit
    }
    /* Strategic Goals image card: remove inner padding and clip to rounded corners */
    #strategic-goals .card{ border-radius:12px; overflow:hidden; }
    #strategic-goals .card .body{ padding:0; }
    #strategic-goals img{ display:block; width:100%; height:auto; }
  </style>
</head>

<body> <!-- Top ribbon (matches homepage) -->
  <div class="topbar" role="banner">
    <div class="container topbar-inner">
      <div class="seal" aria-hidden="true"> <a href="../homepage.php"> <img src="../images/PUPLogo.png"
            alt="PUP Logo" /> </a> </div>
      <div class="brand" aria-label="Campus name"> <span class="u">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</span>
        <span class="c">Biñan Campus</span> </div>
    </div>
  </div> <!-- Header / Nav (matches homepage) -->
  <header>
    <div class="container nav">
      <div class="brand-nav">
        <div class="seal" aria-hidden="true"> <a href="../homepage.php"> <img src="../images/PUPLogo.png"
              alt="PUP Logo" /> </a> </div>
        <div class="brand" aria-label="Campus name"> <span class="u">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</span>
          <span class="c">Bi�an Campus</span> </div>
      </div>
      <nav aria-label="Primary" class="menu" id="menu"> <a href="../homepage.php">Home</a> <a class="is-active"
          href="./about.php">About</a>
        <!-- DROPDOWN REMOVED        <div class="has-dropdown">          <a class="is-active" href="./about.php">About ▾</a>          <div class="dropdown" role="menu" aria-label="About submenu">            <a href="./about.php#overview" role="menuitem">Campus Overview</a>            <a href="./about.php#vision-mission" role="menuitem">Mission &amp; Vision</a>            <a href="./about.php#history" role="menuitem">History</a>            <a href="./about.php#values" role="menuitem">Core Values</a>          </div>        </div> -->
        <a href="./programs.php">Academic Programs</a> <a href="./admission_guide.php">Admissions</a> <a
          href="./services.php">Student Services</a> <a href="./event.php">Events</a> <a
          href="./contact.php">Contact</a> </nav>
      <form class="search-form" action="../search.php" method="get"> <input type="text" name="q"
          placeholder="Search..." aria-label="Search"> </form>
    </div>
    <!--    <div class="mobile-panel" id="mobilePanel" aria-hidden="true">      <nav class="mobile-menu" aria-label="Mobile">        <a href="../homepage.php">Home</a>        <a class="is-active" href="./about.php">About</a>        <a href="./programs.php">Academic Programs</a>        <a href="./admission_guide.php">Admissions</a>        <a href="./services.php">Student Services</a>        <a href="./event.php">Events</a>        <a href="./contact.php">Contact</a>      </nav>    </div>    -->
  </header>
  <main id="content"> <!-- HERO (styled like homepage hero) -->
    <section class="hero">
      <div class="container hero-inner">
        <div>
          <h1>About <span class="accent">PUP Biñan Campus</span></h1>
          <p> Learn about our history, vision, mission, and the values that guide our community of students, faculty,
            staff, partners, and alumni. </p>
        </div>
        <!--        <aside class="hero-card" aria-label="At a glance">          <div class="head">At a Glance</div>          <div class="list">            <span class="pill"><span class="date">Location</span> Sto. Tomas, Biñan, Laguna</span>            <span class="pill"><span class="date">Focus</span> Industry-aligned programs</span>            <span class="pill"><span class="date">Community</span> Students • Faculty • Alumni</span>          </div>        </aside>        -->
      </div>
    </section> <!-- Quick dropdown to jump within About page -->
    <!-- <section class="section" aria-label="About section selector">      <div class="container about-select-wrap">        <label for="aboutJump"><b>Jump to:</b></label>        <select id="aboutJump" class="about-select" aria-label="Jump to About section">          <option value="">Select a section…</option>          <option value="#overview">Campus Overview</option>          <option value="#vision-mission">Mission &amp; Vision</option>          <option value="#history">History</option>          <option value="#values">Core Values</option>        </select>      </div>    </section> -->
    <!-- OVERVIEW + IMAGE (cards & grid like homepage sections) -->
    <section class="section" id="overview">
      <div class="container"> <!-- LEFT: Content -->
        <article class="card">
          <div class="body"> <img src="../images/pupcollab.png" alt="PUP Biñan campus collaboration collage"
              style="width:100%;height:auto;aspect-ratio:4/3;object-fit:cover;border-radius:12px;margin-bottom:.75rem" />
            <h2>Campus Overview</h2>
            <p> <strong>PUP Biñan</strong> is a local campus of the Polytechnic University of the Philippines (PUP),
              part of the country’s largest state university by student population. With more than twenty campuses and
              over 97,000 students across the system, PUP delivers inclusive, affordable public higher education
              nationwide <sup><a href="#ov-ref-1">[1]</a></sup>. </p>
            <p> The Biñan campus was established through a Memorandum of Agreement between the City/Municipality of
              Biñan and PUP on <time datetime="2009-09-15">September 15, 2009</time>. Its main site sits in Barangay
              Zapote, serving learners from Biñan and neighboring areas in Laguna. From the outset, the campus aligned
              offerings to regional workforce needs in information technology, education, social science, business, and
              engineering <sup><a href="#ov-ref-1">[1]</a></sup>. </p>
            <h3>Academic Programs</h3>
            <p> Academic programs span technology, management, and applied sciences. Current curricular offerings
              include <sup><a href="#ov-ref-1">[1]</a></sup>: </p>
            <ul>
              <li><strong>Undergraduate Degrees:</strong> BS Information Technology, BS Computer Engineering, BS
                Industrial Engineering, BSBA (Human Resource Management), BS Psychology, BEEd, BSEd (English/Social
                Studies)</li>
              <li><strong>Diploma Programs (Ladderized):</strong> Diploma in Computer Engineering Technology, Diploma in
                Information Technology</li>
            </ul>
            <h3>Expansion (2024)</h3>
            <p> In 2024, Biñan and PUP inaugurated <em>PUP Biñan Campus 2</em> (College of Information Technology and
              Engineering, CITE) in Barangay Canlalay—a four-storey facility designed to expand STEM capacity. The site
              sits on a ~2,500&nbsp;sqm city-acquired lot and houses 18 classrooms and 4 laboratories, supporting
              projected growth in IT and engineering enrollments <sup><a href="#ov-ref-2">[2]</a></sup>. </p>
            <h3>Mandate, Research & Community Linkages</h3>
            <p> Beyond classrooms, PUP’s system-wide mandate emphasizes public service, research, and extension—with
              strategic goals to intensify research, strengthen sustainable and impactful extension programs, and expand
              local–international partnerships. These values guide PUP Biñan’s community work and linkages with LGUs,
              NGOs, and industry <sup><a href="#ov-ref-1">[1]</a></sup>. </p>
            <h3>Student Support & Opportunities</h3>
            <p> Students also benefit from strong opportunity pipelines. The city’s <em>Iskolar ng Biñan</em> program
              covers tuition and miscellaneous fees for qualified scholars enrolled in SUCs and provides allowances of
              ₱10,000 per semester—support many PUP Biñan students can access <sup><a href="#ov-ref-2">[2]</a></sup>.
              Meanwhile, PUP’s ARCDO and the PUP JobPOST portal connect students to internships (OJT), career talks, and
              job fairs in collaboration with partner organizations <sup><a href="#ov-ref-3">[3]</a></sup>. </p>
            <hr>
          </div>
        </article>
      </div>
    </section> <!-- VISION / MISSION -->
    <section class="section mission" id="vision-mission">
      <div class="container split"> <!-- Left: Mission image card -->
        <article class="card">
          <div class="body"> <img src="../images/pupmission.jpg" alt="PUP Biñan Mission"
              style="width:100%;height:autopx;display:block;border-radius:10px" /> </div>
        </article> <!-- Right: Vision image card -->
        <article class="card">
          <div class="body"> <img src="../images/pupvision.jpg" alt="PUP Biñan Vision"
              style="width:100%;height:auto;display:block;border-radius:12px" /> </div>
        </article>
      </div>
    </section> <!-- HISTORY -->
    <section class="section" id="history">
      <div class="container">
        <article class="card">
          <div class="body">
            <h2>History</h2>
            <p> The Polytechnic University of the Philippines – Biñan (PUP Biñan) traces its beginnings to a Memorandum
              of Agreement signed by the Municipality of Biñan and the Polytechnic University of the Philippines on
              <time datetime="2009-09-15">September 15, 2009</time>. This partnership formally established the campus as
              part of PUP’s strategy to widen access to affordable, high-quality public higher education in Laguna. </p>
            <p> From the outset, the campus aligned its offerings with community needs, opening programs in information
              technology, business, education, social sciences, and engineering—fields that match Biñan’s growing
              economy and talent pipeline. </p>
            <p> Throughout the 2010s, PUP Biñan steadily expanded its reach with the combined support of the local
              government and the University. Improved facilities and student services strengthened the campus’s role as
              a community-anchored institution serving learners from Biñan and nearby cities. This LGU–university
              model—where the city invests in education access and PUP provides academic leadership—laid the groundwork
              for a bigger leap in the next decade. </p>
            <p> A major milestone came on <time datetime="2024-02-02">February 2, 2024</time>, during the city’s Araw ng
              Biñan celebration, when the local government inaugurated a second PUP Biñan site in Barangay Canlalay: the
              College of Information Technology and Engineering (PUP-CITE Biñan). Government and media reports describe
              a four-storey academic building designed for STEM, with around 18 regular classrooms and 4 laboratories—a
              facility purpose-built to strengthen IT and engineering education. This expansion significantly increases
              laboratory-intensive capacity and helps accommodate rising demand for PUP programs among Biñanenses and
              students from neighboring areas. </p>
            <p> Today, PUP Biñan stands as a vital member of the PUP network in Laguna. Anchored by its 2009 founding
              and marked by the 2024 opening of the Canlalay site, the campus exemplifies PUP’s enduring commitment to
              accessible, practice-oriented public education. It continues to uphold system-wide standards in curriculum
              and quality assurance while maintaining close partnerships with the city and local stakeholders. As
              programs and facilities evolve, PUP Biñan remains focused on widening opportunity, cultivating local
              talent for regional industry, and delivering education that empowers students to contribute to Biñan’s
              development and to the broader progress of Southern Luzon. </p>
          </div>
        </article>
      </div>
    </section> <!-- CORE VALUES (uses pill/card styling) -->
    <section class="section" id="values">
      <div class="container">
        <h2>Core Values</h2>
        <div class="grid cols-4">
          <div class="card">
            <div class="body"><b>Integrity</b><br><small class="muted">Upholding honesty and accountability.</small>
            </div>
          </div>
          <div class="card">
            <div class="body"><b>Excellence</b><br><small class="muted">Pursuing high standards in learning and
                service.</small></div>
          </div>
          <div class="card">
            <div class="body"><b>Service</b><br><small class="muted">Serving the community and the nation.</small></div>
          </div>
          <div class="card">
            <div class="body"><b>Innovation</b><br><small class="muted">Encouraging creative solutions and
                research.</small></div>
          </div>
        </div>
      </div>
    </section>
    <!-- STRATEGIC GOALS (image replacement) -->
    <section class="section" id="strategic-goals">
      <div class="container">
        <h2>Strategic Goals</h2>
        <article class="card">
          <div class="body">
            <img src="../images/strategicgoals.jpg" alt="Strategic Goals">
          </div>
        </article>
      </div>

      <!-- <div id="visitor-stats" style="margin-top:20px; padding:10px; border-top:1px solid #ddd;">
    <p>Visitors Today: <span id="today">0</span></p>
    <p>Visitors This Week: <span id="week">0</span></p>
    <p>Visitors This Month: <span id="month">0</span></p>
    <p>Total Visitors: <span id="total">0</span></p>
  </div>

<div style="width:80%; max-width:600px; margin:20px auto;">
  <canvas id="visitorsChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch("../DATAANALYTICS/visitors_chart.php")
  .then(res => res.json())
  .then(data => {
    const labels = data.map(item => item.day);
    const values = data.map(item => item.visits);

 const ctx = document.getElementById("visitorsChart").getContext("2d");

     const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "#f3b233");   // top color
    gradient.addColorStop(0.4, "#540013"); // middle color
    gradient.addColorStop(0.9, "#ffffffff");   // bottom color  

    new Chart(document.getElementById("visitorsChart"), {
      type: "bar", // you can change to 'bar'
      data: {
        labels: labels,
        datasets: [{
          label: "Visitors",
          data: values,
          borderColor: "#f3b233",
          backgroundColor: gradient,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true }
        },
        scales: {
          x: { title: { display: true, text: "Date" }},
          y: { title: { display: true, text: "Number of Visitors" }, beginAtZero: true }
        }
      }
    });
  })
  .catch(err => console.error("Chart error:", err));
</script>

<script>
fetch("../DATAANALYTICS/visitors.php")
  .then(res => res.json())
  .then(data => {
    document.getElementById("today").textContent = data.today;
    document.getElementById("week").textContent  = data.week;
    document.getElementById("month").textContent = data.month;
    document.getElementById("total").textContent = data.total;
  })
  .catch(err => console.error("Visitor stats error:", err));
</script> -->
    </section>
    <!-- CAMPUS LEADERSHIP    <section class="section" id="leadership">      <div class="container">        <article class="card">          <div class="body">            <h2>Campus Leadership</h2>            <p>List your campus director and unit heads here (names, positions, optional photos and contacts).</p>            <ul>              <li><b>Campus Director:</b> Name</li>              <li><b>Academic Affairs:</b> Name</li>              <li><b>Student Affairs:</b> Name</li>              <li><b>Registrar:</b> Name</li>            </ul>          </div>        </article>      </div>    </section>  </main> -->
    <!-- Footer (same as homepage) -->
    <footer id="contact">
      <div class="container foot">
        <div>
          <p>Page Views: <?php echo $page_views; ?></p>
  <p>Total Visitors: <?php echo $total_visitors; ?></p>
        </div>        
        
        <div>
          <h4>About PUP Biñan</h4>
          <p>PUP Biñan Campus is part of the country's largest state university system, committed to accessible and
            excellent public higher education.</p>
        </div>
        <div>
          <h4>Contact</h4>
          <p>Sto. Tomas, Biñan, Laguna<br />Philippines 4024</p>
          <p>Email: <a href="mailto:info.binan@pup.edu.ph">info.binan@pup.edu.ph</a><br />Phone: (xxx) xxx xxxx</p>
        </div>
        <div>
          <h4>Connect</h4>
          <p><a href="#">Facebook</a> · <a href="#">X</a> · <a href="#">YouTube</a></p>
          <p><a href="#">Feedback & FAQs</a></p>
        </div>
      </div>
      <div class="container sub">© <span id="year"></span> PUP Biñan Campus. For demo/UI purposes only.</div>
    </footer>
    <script>      // Dropdown jump behavior for About page      (function () {        const sel = document.getElementById('aboutJump');        if (!sel) return;        sel.addEventListener('change', function () {          const val = sel.value;          if (!val) return;          const target = document.querySelector(val);          if (target && typeof target.scrollIntoView === 'function') {            target.scrollIntoView({ behavior: 'smooth', block: 'start' });          } else {            location.hash = val;          }          sel.selectedIndex = 0;        });      })();    </script>
    <button id="backTop" class="btn" aria-label="Back to top" title="Back to top">↑ Top</button>
</body>
</html>

