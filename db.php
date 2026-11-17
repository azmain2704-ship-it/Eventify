<?php
/* =============================================
   Eventify - shared database connection + helpers
   One place for connection details instead of copying them into every page.
   Values can be overridden with environment variables, so real credentials
   never need to be committed to GitHub.
   ============================================= */

// Keep mysqli's old behaviour (return false on error) so the
// "if (!$conn)" checks in each page keep working on PHP 8.1+.
mysqli_report(MYSQLI_REPORT_OFF);

function db_connect() {
    $host     = getenv("EVENTIFY_DB_HOST") ?: "localhost";
    $dbname   = getenv("EVENTIFY_DB_NAME") ?: "cmoeventmanagement";
    $username = getenv("EVENTIFY_DB_USER") ?: "root";
    $password = getenv("EVENTIFY_DB_PASS") ?: "";

    $conn = @mysqli_connect($host, $username, $password, $dbname);
    if ($conn) {
        mysqli_set_charset($conn, "utf8mb4");
    }
    return $conn;
}

/* Escape text before printing it into HTML.
   Stops anyone typing <script> into a form and having it run on other visitors' screens (XSS). */
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

/* Only allow real web links (http/https) for images and videos.
   Blocks things like "javascript:alert(1)" being saved as a link. */
function safe_url($url) {
    $url = trim((string) $url);
    if ($url === "") {
        return "";
    }
    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    if (($scheme === "http" || $scheme === "https") && filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    return "";
}

/* Look up one event by its id using a prepared statement. Returns an array or null. */
function get_event($conn, $event_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM event_details WHERE event_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ?: null;
}
