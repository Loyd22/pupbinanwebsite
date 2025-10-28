<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Search Results</title>
    <meta name="description" content="Site search results" />
    <link rel="stylesheet" href="../asset/css/site.css" />
    <style>
        .search-page {
            padding: 24px 0;
        }

        .search-input {
            display: flex;
            gap: .5rem;
            margin: 12px 0 24px;
        }

        .search-input input {
            flex: 1;
            padding: .6rem .7rem;
            border-radius: .65rem;
            border: 1px solid var(--border);
        }

        .result {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            padding: .9rem;
        }

        .result h3 {
            margin: .2rem 0 .4rem;
            font-size: 1.05rem;
        }

        .result small {
            color: var(--muted);
        }

        .result+.result {
            margin-top: .85rem;
        }

        .snippet {
            color: #4b5563;
        }

        .snippet mark {
            background: #fff3bf;
            color: inherit;
            border-radius: .15rem;
        }

        .muted-note {
            color: var(--muted);
            margin-top: .5rem;
        }

        .loader {
            color: var(--muted);
        }
    </style>
    <script defer src="../asset/js/chatbot.js"></script>
</head>

<body>
    <header>
        <div class="container nav">
            <div class="brand-nav">
                <div class="seal" aria-hidden="true"> <a href="../homepage.php"> <img src="../images/PUPLogo.png"
                            alt="PUP Logo" /> </a> </div>
                <div class="brand" aria-label="Campus name"> <span class="u">POLYTECHNIC UNIVERSITY OF THE
                        PHILIPPINES</span> <span class="c">Bi�an Campus</span> </div>
            </div>
            <nav aria-label="Primary" class="menu" id="menu"> <a href="../homepage.php">Home</a> <a
                    href="about.php">About</a> <a href="programs.php">Academic Programs</a> <a
                    href="admission_guide.php">Admissions</a> <a href="services.php">Student Services</a> <a
                    href="event.php">Events</a> <a href="contact.php">Contact</a> </nav>
            <form class="search-form" action="#search" method="get"> <input id="q" type="text" name="q"
                    placeholder="Search..." aria-label="Search"> </form>
        </div>
    </header>
    <main class="container search-page" id="content">
        <h1>Search Results</h1>
        <div id="status" class="loader">Loading…</div>
        <div id="results" class="results" aria-live="polite"></div>
    </main>
    <script defer src="../asset/js/homepage.js"></script>
    <script defer src="../asset/js/search.js"></script>
</body>

</html>
