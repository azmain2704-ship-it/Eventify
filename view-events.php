<!DOCTYPE html>
<html>
<head>
    <title>Eventify - View Events</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container">
            <h1><span>Event</span>ify</h1>
            <p style="color:#aaaaaa; font-size:14px; font-family:Arial,sans-serif;">Your platform for public events</p>
        </div>
    </header>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add-event.php">Add Event</a></li>
            <li><a class="active" href="view-events.php">View Events</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">

            <h2 class="page-title">All Events</h2>

            <?php
            // Shared connection details and helpers (see db.php)
            require_once "db.php";

            // Connect to the MySQL database
            $conn = db_connect();

            // Check if the connection worked
            if (!$conn) {
                echo "<div class='error-banner'>Could not connect to the database. Please try again later.</div>";
            } else {

                // SQL query to retrieve all events from the event_details table
                $sql = "SELECT * FROM event_details ORDER BY start_date ASC";

                // Run the query
                $result = mysqli_query($conn, $sql);

                // Check if there are any events in the database
                if (mysqli_num_rows($result) > 0) {

                    // Loop through each row returned and display as a card
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='event-card'>";
                        echo "<span class='event-category'>" . e($row["event_category"]) . "</span>";
                        echo "<h3>" . e($row["event_name"]) . "</h3>";
                        echo "<p>" . e($row["event_description"]) . "</p>";
                        echo "<p><strong>Keywords:</strong> " . e($row["keywords"]) . "</p>";
                        echo "<p class='event-dates'>Start: " . e($row["start_date"]) . " &nbsp;|&nbsp; End: " . e($row["end_date"]) . "</p>";

                        // Only show image if an image URL was provided
                        $image_url = safe_url($row["image_url"]);
                        if ($image_url != "") {
                            echo "<br><img src='" . e($image_url) . "' alt='Event image' style='max-width:300px; margin-top:10px;'>";
                        }

                        // Only show video link if a video URL was provided
                        $video_url = safe_url($row["video_url"]);
                        if ($video_url != "") {
                            echo "<br><a href='" . e($video_url) . "' target='_blank' rel='noopener noreferrer' style='color:#e8a838; font-family:Arial,sans-serif; font-size:13px;'>Watch video &rarr;</a>";
                        }

                        // Edit and Delete buttons
                        echo "<br><br>";
                        echo "<a href='edit-event.php?event_id=" . (int) $row["event_id"] . "' style='display:inline-block; background-color:#1a1a2e; color:#e8a838; padding:6px 16px; text-decoration:none; font-family:Arial,sans-serif; font-size:13px; margin-right:8px;'>Edit</a>";
                        echo "<a href='delete-event.php?event_id=" . (int) $row["event_id"] . "' style='display:inline-block; background-color:#cc0000; color:#ffffff; padding:6px 16px; text-decoration:none; font-family:Arial,sans-serif; font-size:13px;'>Delete</a>";

                        echo "</div>";
                    }

                } else {
                    // No events found in the database
                    echo "<div class='no-events'>";
                    echo "<p>No events have been added yet.</p>";
                    echo "<p><a href='add-event.php' style='color:#e8a838;'>Add the first event &rarr;</a></p>";
                    echo "</div>";
                }

                // Close the database connection
                mysqli_close($conn);
            }
            ?>

        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>Eventify &copy; 2025 | SEN4001 Web Design &amp; Databases</p>
        <p>Cardiff Metropolitan University</p>
    </footer>

</body>
</html>
