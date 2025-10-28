<?php
include "DATAANALYTICS/db.php";

$ip = $_SERVER['REMOTE_ADDR'];
$sql = "INSERT INTO visitors (ip_address) VALUES (?)";
$stmt = $conn->prepare($sql);
if ($stmt) {
  $stmt->bind_param("s", $ip);
  $stmt->execute();
  $stmt->close();
}

$settingDefaults = [
  'site_title' => 'POLYTECHNIC UNIVERSITY OF THE PHILIPPINES',
  'campus_name' => 'Biñan Campus',
  'hero_heading' => 'Serving the Nation through Quality Public Education',
  'hero_text' => 'Welcome to the PUP Biñan Campus homepage - your hub for announcements, admissions, academic programs, student services, and campus life.',
  'logo_path' => 'images/PUPLogo.png',
  'hero_image_path' => '',
  'footer_about' => 'PUP Biñan Campus is part of the country\'s largest state university system, committed to accessible and excellent public higher education.',
  'footer_address' => "Sto. Tomas, Biñan, Laguna\nPhilippines 4024",
  'footer_email' => 'info.binan@pup.edu.ph',
  'footer_phone' => '(xxx) xxx xxxx'
];

function fetchSettings(mysqli $conn, array $keys): array
{
  if (empty($keys)) {
    return [];
  }

  $placeholders = implode(',', array_fill(0, count($keys), '?'));
  $types = str_repeat('s', count($keys));
  $sql = "SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ($placeholders)";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    return [];
  }

  $stmt->bind_param($types, ...$keys);
  $stmt->execute();
  $result = $stmt->get_result();

  $settings = [];
  while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
  }
  $stmt->close();

  return $settings;
}

function renderRichText(?string $value): string
{
  if ($value === null || $value === '') {
    return '';
  }

  $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

  $decoded = preg_replace('#<(script|style)[^>]*>.*?</\1>#is', '', $decoded);
  $decoded = preg_replace('/\sstyle=("|\').*?\1/i', '', $decoded);
  $decoded = preg_replace('/\son[a-z]+\s*=\s*("|\').*?\1/i', '', $decoded);

  $allowedTags = '<p><br><strong><em><b><i><u><ul><ol><li><a>';
  return trim(strip_tags($decoded, $allowedTags));
}

function fetchSocialLinks(mysqli $conn): array
{
  if ($check = $conn->query("SHOW TABLES LIKE 'social_links'")) {
    if ($check->num_rows === 0) {
      $check->free();
      return [];
    }
    $check->free();
  } else {
    return [];
  }

  $sql = "SELECT label, url
            FROM social_links
            ORDER BY created_at ASC, id ASC";

  $result = $conn->query($sql);
  if (!$result) {
    return [];
  }

  $links = [];
  while ($row = $result->fetch_assoc()) {
    $label = trim((string)($row['label'] ?? ''));
    $url = trim((string)($row['url'] ?? ''));
    if ($label !== '' && $url !== '') {
      $links[] = [
        'label' => $label,
        'url' => $url
      ];
    }
  }
  $result->free();

  return $links;
}

function fetchAnnouncements(mysqli $conn, int $limit = 3): array
{
  $sql = "SELECT id, title, body, category, publish_date, cta_label, cta_url
            FROM announcements
            WHERE is_published = 1
            ORDER BY COALESCE(publish_date, created_at) DESC
            LIMIT ?";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    return [];
  }

  $stmt->bind_param('i', $limit);
  $stmt->execute();
  $result = $stmt->get_result();
  $items = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  return $items;
}

function fetchNews(mysqli $conn, int $limit = 3): array
{
  $sql = "SELECT id, title, summary, image_path, publish_date
            FROM news
            WHERE is_published = 1
            ORDER BY COALESCE(publish_date, created_at) DESC
            LIMIT ?";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    return [];
  }

  $stmt->bind_param('i', $limit);
  $stmt->execute();
  $result = $stmt->get_result();
  $items = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  return $items;
}

function fetchMedia(mysqli $conn, string $type, int $limit): array
{
  $sql = "SELECT id, title, description, file_path, video_url, uploaded_at
            FROM media_library
            WHERE media_type = ?
            ORDER BY uploaded_at DESC
            LIMIT ?";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    return [];
  }

  $stmt->bind_param('si', $type, $limit);
  $stmt->execute();
  $result = $stmt->get_result();
  $items = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  return $items;
}

function formatDate(?string $date): string
{
  if (!$date) {
    return 'Draft';
  }

  $timestamp = strtotime($date);
  return $timestamp ? date('M j, Y', $timestamp) : $date;
}

function excerpt(string $text, int $limit = 110): string
{
  $clean = trim(strip_tags($text));
  if ($clean === '') {
    return '';
  }

  if (function_exists('mb_strlen')) {
    if (mb_strlen($clean) <= $limit) {
      return $clean;
    }
    return rtrim(mb_substr($clean, 0, $limit - 3)) . '...';
  }

  if (strlen($clean) <= $limit) {
    return $clean;
  }

  return rtrim(substr($clean, 0, $limit - 3)) . '...';
}

function buildVideoEmbed(?string $url): ?string
{
  if (!$url) {
    return null;
  }

  if (preg_match('~youtu\.be/([A-Za-z0-9_-]{11})~', $url, $matches)) {
    return 'https://www.youtube.com/embed/' . $matches[1];
  }

  if (preg_match('~youtube\.com/watch\?v=([A-Za-z0-9_-]{11})~', $url, $matches)) {
    return 'https://www.youtube.com/embed/' . $matches[1];
  }

  if (preg_match('~vimeo\.com/(\d+)~', $url, $matches)) {
    return 'https://player.vimeo.com/video/' . $matches[1];
  }

  return null;
}

$settings = array_merge(
  $settingDefaults,
  fetchSettings($conn, array_keys($settingDefaults))
);

$logoPath = $settings['logo_path'] ?: 'images/PUPLogo.png';
$heroImagePath = $settings['hero_image_path'];
$heroInlineStyle = '';
if ($heroImagePath) {
  $heroInlineStyle = ' style="background-image: linear-gradient(0deg, rgba(0,0,0,0.55), rgba(0,0,0,0.35)), url(' . htmlspecialchars($heroImagePath) . ');"';
}

$announcements = fetchAnnouncements($conn);
$newsItems = fetchNews($conn);
$mediaImages = fetchMedia($conn, 'image', 3);
$mediaVideos = fetchMedia($conn, 'video', 1);
$socialLinks = fetchSocialLinks($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PUP Bi&ntilde;an Campus - Official Website</title>
  <meta name="description"
    content="Polytechnic University of the Philippines - Bi&ntilde;an Campus. UI-only, static HTML/CSS/JS." />
  <meta name="theme-color" content="#7a0019" />
  <link rel="stylesheet" href="asset/css/site.css" />
  <link rel="stylesheet" href="asset/css/home.css" />
  <script defer src="asset/js/homepage.js"></script>
  <script defer src="asset/js/chatbot.js"></script>
</head>

<body class="home" data-wp-base="">
  <header> <!-- Top row: logo + campus text (inside header) -->
    <div class="topbar" role="banner">
      <div class="container topbar-inner">
        <div class="seal" aria-hidden="true"> <a href="homepage.php"> <img
              src="<?php echo htmlspecialchars($logoPath); ?>" alt="PUP Logo" />
          </a> </div>
        <div class="brand" aria-label="Campus name"> <span
            class="u"><?php echo htmlspecialchars($settings['site_title']); ?></span>
          <span class="c"><?php echo htmlspecialchars($settings['campus_name']); ?></span>
        </div>
      </div>
    </div> <!-- Navigation bar with logo + university name -->
    <div class="container nav">
      <div class="brand-nav">
        <div class="seal" aria-hidden="true"> <a href="homepage.php"> <img
              src="<?php echo htmlspecialchars($logoPath); ?>" alt="PUP Logo" />
          </a> </div>
        <div class="brand" aria-label="Campus name"> <span
            class="u"><?php echo htmlspecialchars($settings['site_title']); ?></span>
          <span class="c"><?php echo htmlspecialchars($settings['campus_name']); ?></span>
        </div>
      </div>
      <nav aria-label="Primary" class="menu" id="menu"> <a class="is-active" href="#">Home</a>
        <div class="has-dropdown"> <a href="pages/about.php">About</a>
          <div class="dropdown" role="menu" aria-label="About submenu"> <a href="pages/about.php#overview"
              role="menuitem">Campus Overview</a> <a href="pages/about.php#vision-mission" role="menuitem">Mission
              &amp; Vision</a> <a href="pages/about.php#history" role="menuitem">History</a> <a
              href="pages/about.php#values" role="menuitem">Core Values</a> </div>
        </div> <a href="pages/programs.php">Academic Programs</a> <a href="pages/admission_guide.php">Admissions</a>
        <a href="pages/services.php">Student Services</a> <a href="pages/event.php">Events</a> <a
          href="pages/contact.php">Contact</a>
      </nav>
      <form class="search-form" action="#search" method="get" role="search" aria-label="Site search"> <input type="text"
          name="q" placeholder="Search..." aria-label="Search" /> </form>
    </div>
  </header>
  <main id="content"> <!-- HERO -->
    <section class="hero" <?php echo $heroInlineStyle; ?>>
      <div class="container hero-inner">
        <div>
          <h1><?php echo htmlspecialchars($settings['hero_heading']); ?></h1>
          <p><?php echo nl2br(htmlspecialchars($settings['hero_text'])); ?></p>
        </div>
      </div>
    </section>
    <section class="section" id="news" data-wp-cat="news" data-wp-count="3">
      <div class="container">
        <article class="card" aria-labelledby="newsHeading">
          <h3 id="newsHeading">News</h3>
          <div class="body">
            <div class="news-grid">
              <?php if (!empty($newsItems)): ?>
                <?php foreach ($newsItems as $news): ?>
                  <div class="news">
                    <div class="img"><img src="<?php echo htmlspecialchars($news['image_path'] ?: 'images/pupsite.jpg'); ?>"
                        alt="<?php echo htmlspecialchars($news['title']); ?>"></div>
                    <div class="txt">
                      <h4><?php echo htmlspecialchars($news['title']); ?></h4>
                      <p><?php echo htmlspecialchars(excerpt($news['summary'])); ?></p>
                      <small><?php echo htmlspecialchars(formatDate($news['publish_date'])); ?></small>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p>No news published yet. Please check back soon.</p>
              <?php endif; ?>
            </div>
            <p class="more-link"><a href="#" aria-label="Go to all news">See all news &rsaquo;</a></p>
          </div>
        </article>
      </div>
    </section> <!-- ANNOUNCEMENTS + EVENTS -->
    <section class="section" id="announcements" data-wp-cat="announcements" data-wp-count="3">
      <div class="container split">
        <article class="card" aria-labelledby="annoHeading">
          <h3 id="annoHeading">Announcements</h3>
          <div class="body">
            <div class="annos">
              <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $announcement): ?>
                  <div class="anno">
                    <strong><?php echo htmlspecialchars($announcement['title']); ?></strong>
                    <small>Published:
                      <?php echo htmlspecialchars(formatDate($announcement['publish_date'])); ?>
                      <?php if (!empty($announcement['category'])): ?>
                        &middot; Category: <?php echo htmlspecialchars($announcement['category']); ?><?php endif; ?></small>
                    <?php if (!empty($announcement['cta_url'])): ?>
                      <a
                        href="<?php echo htmlspecialchars($announcement['cta_url']); ?>"><?php echo htmlspecialchars($announcement['cta_label'] ?: 'Read details'); ?></a>
                    <?php else: ?>
                      <p><?php echo htmlspecialchars(excerpt($announcement['body'])); ?></p>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p>No announcements at the moment.</p>
              <?php endif; ?>
            </div>
            <p class="more-link"><a href="#" aria-label="Go to all announcements">See all announcements &rsaquo;</a></p>
          </div>
        </article>
        <article class="card" id="events" aria-labelledby="eventsHeading">
          <div class="toolbar">
            <h3 id="eventsHeading">Events Calendar</h3> <span class="tag">Google Calendar</span>
          </div>
          <div class="body">
            <div class="annos">
              <div class="anno"> <strong>IT Week 2025</strong> <small>Oct 10-14 &middot; Talks, exhibits,
                  competitions</small> <a href="#">View schedule</a> </div>
              <div class="anno"> <strong>Campus Clean-up Drive</strong> <small>Oct 3 &middot; Student Affairs</small> <a
                  href="#">Join activity</a> </div>
              <div class="anno"> <strong>Research Colloquium</strong> <small>Sep 30 &middot; Library</small> <a
                  href="#">Program details</a> </div>
            </div>
          </div> <iframe class="calendar" title="Campus Calendar"
            src="https://calendar.google.com/calendar/embed?src=en.philippines%23holiday%40group.v.calendar.google.com&ctz=Asia%2FManila"></iframe>
        </article>
      </div>
    </section>
    <?php if (!empty($mediaImages) || !empty($mediaVideos)): ?>
      <section class="section" id="media">
        <div class="container">
          <article class="card" aria-labelledby="mediaHeading">
            <h3 id="mediaHeading">Media Highlights</h3>
            <div class="body">
              <div class="news-grid">
                <?php foreach ($mediaImages as $image): ?>
                  <div class="news">
                    <div class="img"><img src="<?php echo htmlspecialchars($image['file_path']); ?>"
                        alt="<?php echo htmlspecialchars($image['title']); ?>"></div>
                    <div class="txt">
                      <h4><?php echo htmlspecialchars($image['title']); ?></h4>
                      <?php if (!empty($image['description'])): ?>
                        <p><?php echo htmlspecialchars(excerpt($image['description'])); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php foreach ($mediaVideos as $video): ?>
                  <div class="news">
                    <div class="img">
                      <?php $embed = buildVideoEmbed($video['video_url']); ?>
                      <?php if ($embed): ?>
                        <iframe src="<?php echo htmlspecialchars($embed); ?>"
                          title="<?php echo htmlspecialchars($video['title']); ?>"
                          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                          allowfullscreen></iframe>
                      <?php else: ?>
                        <a href="<?php echo htmlspecialchars($video['video_url']); ?>" target="_blank" rel="noopener">Watch
                          video</a>
                      <?php endif; ?>
                    </div>
                    <div class="txt">
                      <h4><?php echo htmlspecialchars($video['title']); ?></h4>
                      <?php if (!empty($video['description'])): ?>
                        <p><?php echo htmlspecialchars(excerpt($video['description'])); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </article>
        </div>
      </section>
    <?php endif; ?>
  </main>
  <footer id="contact">
    <div class="container foot">

      <div>

        <p id="today"> </p>
        <p id="week"> </p>
        <p id="month"> </p>
        <p id="total"> </p>
      </div>

      <div>
        <h4>About <?php echo htmlspecialchars($settings['site_title'] . " " . $settings['campus_name']); ?></h4>
        <?php echo renderRichText($settings['footer_about']); ?>
      </div>
      <div>
        <h4>Contact</h4>
        <p><?php echo nl2br(htmlspecialchars($settings['footer_address'])); ?></p>
        <p>Email: <a href="mailto:<?php echo htmlspecialchars($settings['footer_email']); ?>">
            <?php echo htmlspecialchars($settings['footer_email']); ?></a>
          <br />Phone: <?php echo htmlspecialchars($settings['footer_phone']); ?>
        </p>
      </div>
      <div>
        <h4>Connect</h4>
        <?php if (!empty($socialLinks)): ?>
          <p>
            <?php foreach ($socialLinks as $index => $link): ?>
              <?php if ($index > 0): ?>&middot; <?php endif; ?>
              <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" rel="noopener">
                <?php echo htmlspecialchars($link['label']); ?>
              </a>
            <?php endforeach; ?>
          </p>
        <?php else: ?>
          <p>No social links configured yet.</p>
        <?php endif; ?>
        <p><a href="#">Feedback &amp; FAQs</a></p>
      </div>
    </div>
    <div class="container sub"> &copy; <span id="year"></span>
      <?php echo htmlspecialchars($settings['site_title'] . ' ' . $settings['campus_name']); ?>. </div>
  </footer>

  <Script>
    fetch("./DATAANALYTICS/visitors.php")
      .then(response => response.json())
      .then(data => {
        document.getElementById("today",
        ).textContent = "Visitors Today: " + data.today;

        document.getElementById("week",
        ).textContent = "Visitors This Week: " + data.week;

        document.getElementById("month",
        ).textContent = "Visitors This Month: " + data.month;

        document.getElementById("total",
        ).textContent = "Total Visitors: " + data.total;

      })
      .catch(err => console.error("Error fetching visitor data: ", err));

  </Script>
</body>

</html>
