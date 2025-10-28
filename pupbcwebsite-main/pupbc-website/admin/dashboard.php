<?php
declare(strict_types=1);

$pageTitle = 'Dashboard';
$currentSection = 'dashboard';
require_once __DIR__ . '/includes/header.php';

$announcementStats = ['total' => 0, 'published' => 0, 'drafts' => 0];
if ($result = $conn->query("SELECT COUNT(*) AS total, SUM(is_published = 1) AS published, SUM(is_published = 0) AS drafts FROM announcements")) {
    $row = $result->fetch_assoc() ?: [];
    $announcementStats['total'] = (int)($row['total'] ?? 0);
    $announcementStats['published'] = (int)($row['published'] ?? 0);
    $announcementStats['drafts'] = (int)($row['drafts'] ?? 0);
    $result->free();
}

$newsStats = ['total' => 0, 'published' => 0, 'drafts' => 0];
if ($result = $conn->query("SELECT COUNT(*) AS total, SUM(is_published = 1) AS published, SUM(is_published = 0) AS drafts FROM news")) {
    $row = $result->fetch_assoc() ?: [];
    $newsStats['total'] = (int)($row['total'] ?? 0);
    $newsStats['published'] = (int)($row['published'] ?? 0);
    $newsStats['drafts'] = (int)($row['drafts'] ?? 0);
    $result->free();
}

$mediaStats = ['total' => 0, 'images' => 0, 'videos' => 0];
if ($result = $conn->query("SELECT COUNT(*) AS total, SUM(media_type = 'image') AS images, SUM(media_type = 'video') AS videos FROM media_library")) {
    $row = $result->fetch_assoc() ?: [];
    $mediaStats['total'] = (int)($row['total'] ?? 0);
    $mediaStats['images'] = (int)($row['images'] ?? 0);
    $mediaStats['videos'] = (int)($row['videos'] ?? 0);
    $result->free();
}

$visitorStats = ['today' => 0, 'week' => 0, 'month' => 0, 'total' => 0];
$hasVisitorTable = false;
if ($result = $conn->query("SHOW TABLES LIKE 'visitors'")) {
    $hasVisitorTable = $result->num_rows > 0;
    $result->free();
}

if ($hasVisitorTable) {
    $visitorDateColumn = 'visited_at';
    if ($result = $conn->query("SHOW COLUMNS FROM visitors LIKE 'visited_at'")) {
        if ($result->num_rows === 0) {
            if ($fallback = $conn->query("SHOW COLUMNS FROM visitors LIKE 'visit_time'")) {
                if ($fallback->num_rows > 0) {
                    $visitorDateColumn = 'visit_time';
                }
                $fallback->free();
            }
        }
        $result->free();
    }

    $column = $visitorDateColumn;
    $visitorQueries = [
        'today' => "SELECT COUNT(*) AS count FROM visitors WHERE DATE($column) = CURDATE()",
        'week' => "SELECT COUNT(*) AS count FROM visitors WHERE YEARWEEK($column, 1) = YEARWEEK(CURDATE(), 1)",
        'month' => "SELECT COUNT(*) AS count FROM visitors WHERE YEAR($column) = YEAR(CURDATE()) AND MONTH($column) = MONTH(CURDATE())",
        'total' => "SELECT COUNT(*) AS count FROM visitors"
    ];

    foreach ($visitorQueries as $key => $sql) {
        if ($result = $conn->query($sql)) {
            $row = $result->fetch_assoc() ?: [];
            $visitorStats[$key] = (int)($row['count'] ?? 0);
            $result->free();
        }
    }
}

$recentAnnouncements = [];
if ($result = $conn->query("SELECT id, title, category, COALESCE(publish_date, created_at) AS display_date FROM announcements ORDER BY COALESCE(publish_date, created_at) DESC LIMIT 5")) {
    while ($row = $result->fetch_assoc()) {
        $recentAnnouncements[] = [
            'id' => (int)($row['id'] ?? 0),
            'title' => (string)($row['title'] ?? ''),
            'category' => (string)($row['category'] ?? ''),
            'display_date' => $row['display_date'] ?? null
        ];
    }
    $result->free();
}

$recentNews = [];
if ($result = $conn->query("SELECT id, title, COALESCE(publish_date, created_at) AS display_date FROM news ORDER BY COALESCE(publish_date, created_at) DESC LIMIT 5")) {
    while ($row = $result->fetch_assoc()) {
        $recentNews[] = [
            'id' => (int)($row['id'] ?? 0),
            'title' => (string)($row['title'] ?? ''),
            'display_date' => $row['display_date'] ?? null
        ];
    }
    $result->free();
}

$recentMedia = [];
if ($result = $conn->query("SELECT id, title, media_type, uploaded_at FROM media_library ORDER BY uploaded_at DESC LIMIT 5")) {
    while ($row = $result->fetch_assoc()) {
        $recentMedia[] = [
            'id' => (int)($row['id'] ?? 0),
            'title' => (string)($row['title'] ?? ''),
            'media_type' => (string)($row['media_type'] ?? ''),
            'uploaded_at' => $row['uploaded_at'] ?? null
        ];
    }
    $result->free();
}

$formatDate = static function (?string $value): string {
    if ($value === null || $value === '') {
        return 'Date not set';
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return 'Date not set';
    }

    return date('M j, Y', $timestamp);
};
?>

<section class="card-grid">
    <article class="card">
        <h2>Announcements</h2>
        <p class="metric"><?php echo number_format($announcementStats['total']); ?></p>
        <p>
            <strong>Published:</strong> <?php echo number_format($announcementStats['published']); ?><br>
            <strong>Drafts:</strong> <?php echo number_format($announcementStats['drafts']); ?>
        </p>
        <a class="card__link" href="announcements.php">Manage announcements</a>
    </article>

    <article class="card">
        <h2>News</h2>
        <p class="metric"><?php echo number_format($newsStats['total']); ?></p>
        <p>
            <strong>Published:</strong> <?php echo number_format($newsStats['published']); ?><br>
            <strong>Drafts:</strong> <?php echo number_format($newsStats['drafts']); ?>
        </p>
        <a class="card__link" href="news.php">Manage news</a>
    </article>

    <article class="card">
        <h2>Media Library</h2>
        <p class="metric"><?php echo number_format($mediaStats['total']); ?></p>
        <p>
            <strong>Images:</strong> <?php echo number_format($mediaStats['images']); ?><br>
            <strong>Videos:</strong> <?php echo number_format($mediaStats['videos']); ?>
        </p>
        <a class="card__link" href="media.php">Open media library</a>
    </article>

    <article class="card">
        <h2>Visitor Traffic</h2>
        <p class="metric"><?php echo number_format($visitorStats['total']); ?></p>
        <p>
            <strong>Today:</strong> <?php echo number_format($visitorStats['today']); ?><br>
            <strong>This week:</strong> <?php echo number_format($visitorStats['week']); ?><br>
            <strong>This month:</strong> <?php echo number_format($visitorStats['month']); ?>
        </p>
        <a class="card__link" href="../DATAANALYTICS/visitors.php" target="_blank" rel="noopener">View visitor API</a>
    </article>
</section>

<section class="card">
    <h2>Recent Updates</h2>
    <div class="recent-columns">
        <div>
            <h3>Latest Announcements</h3>
            <?php if ($recentAnnouncements !== []): ?>
                <ul>
                    <?php foreach ($recentAnnouncements as $item): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                            <?php if ($item['category'] !== ''): ?>
                                &middot; <?php echo htmlspecialchars($item['category']); ?>
                            <?php endif; ?>
                            <br>
                            <small><?php echo htmlspecialchars($formatDate($item['display_date'])); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No announcements yet.</p>
            <?php endif; ?>
            <a class="card__link" href="announcements.php">Go to announcements</a>
        </div>

        <div>
            <h3>Latest News</h3>
            <?php if ($recentNews !== []): ?>
                <ul>
                    <?php foreach ($recentNews as $item): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                            <br>
                            <small><?php echo htmlspecialchars($formatDate($item['display_date'])); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No news articles yet.</p>
            <?php endif; ?>
            <a class="card__link" href="news.php">Go to news</a>
        </div>

        <div>
            <h3>Latest Media</h3>
            <?php if ($recentMedia !== []): ?>
                <ul>
                    <?php foreach ($recentMedia as $item): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                            &middot; <?php echo htmlspecialchars(ucfirst($item['media_type'])); ?>
                            <br>
                            <small><?php echo htmlspecialchars($formatDate($item['uploaded_at'])); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No media uploaded yet.</p>
            <?php endif; ?>
            <a class="card__link" href="media.php">Open media library</a>
        </div>
    </div>
</section>

<section class="card">
    <h2>Quick Actions</h2>
    <ul>
        <li><a href="announcements.php">Create a new announcement</a></li>
        <li><a href="news.php">Publish a news story</a></li>
        <li><a href="media.php">Upload media assets</a></li>
        <li><a href="settings.php">Update site settings</a></li>
    </ul>
</section>

<section class="card">
    <h2>Analytics</h2>
    <div class="recent-columns">
        <div>
            <h3>Visits (Last 7 Days)</h3>
            <canvas id="chartVisits7d" height="160"></canvas>
        </div>
        <div>
            <h3>Visitors Summary</h3>
            <canvas id="chartVisitorsSummary" height="160"></canvas>
            <small style="color: var(--muted);">Today, This week, This month</small>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" integrity="sha384-YRz1U8Q3z5l3r0W9m5oCz9aYbT2PZ3dQ1ybrdQ8CwzC3p7k3mQk1N8s2lL7yZ9gB" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const visitsEl = document.getElementById('chartVisits7d');
  const summaryEl = document.getElementById('chartVisitorsSummary');

  // Fetch last 7 days page visits
  fetch('../DATAANALYTICS/visitors_chart.php')
    .then(r => r.json())
    .then(data => {
      const labels = data.map(d => d.label);
      const counts = data.map(d => d.count);
      if (visitsEl) {
        new Chart(visitsEl, {
          type: 'line',
          data: {
            labels,
            datasets: [{
              label: 'Visits',
              data: counts,
              borderColor: '#7a0019',
              backgroundColor: 'rgba(122, 0, 25, 0.15)',
              tension: 0.35,
              fill: true,
              pointRadius: 3,
              pointBackgroundColor: '#7a0019'
            }]
          },
          options: {
            plugins: { legend: { display: false } },
            scales: {
              x: { grid: { display: false } },
              y: { beginAtZero: true, ticks: { precision: 0 } }
            }
          }
        });
      }
    })
    .catch(() => {});

  // Fetch visitors summary (today/week/month)
  fetch('../DATAANALYTICS/visitors.php')
    .then(r => r.json())
    .then(stats => {
      const labels = ['Today', 'This week', 'This month'];
      const counts = [stats.today || 0, stats.week || 0, stats.month || 0];
      if (summaryEl) {
        new Chart(summaryEl, {
          type: 'doughnut',
          data: {
            labels,
            datasets: [{
              data: counts,
              backgroundColor: ['#f3b233', '#7a0019', '#540013'],
              borderWidth: 0
            }]
          },
          options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '60%'
          }
        });
      }
    })
    .catch(() => {});
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
