<!DOCTYPE html>
<html>
<head>
    <title>Eventify - Delete Event</title>
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

            <h2 class="page-title">Delete Event</h2>

            <?php
            // Shared connection details and helpers (see db.php)
            require_once "db.php";

            // Get the event_id passed through the URL
            $event_id = (int) ($_GET["event_id"] ?? 0);

            // Connect to the database
            $conn = db_connect();

            if (!$conn) {
                echo "<div class='error-banner'>Database connection failed. Please try again later.</div>";
            } else {

                // Check if the user has confirmed the deletion
                // Deleting only happens on a POST from the confirm button, never from a plain link,
                // so a crawler or a dodgy link can't wipe events just by visiting a URL.
                if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["confirm"] ?? "") == "yes") {

                    // Run the DELETE query as a prepared statement
                    $stmt = mysqli_prepare($conn, "DELETE FROM event_details WHERE event_id = ?");
                    mysqli_stmt_bind_param($stmt, "i", $event_id);

                    if (mysqli_stmt_execute($stmt)) {
                        // Redirect back to view events after successful deletion
                        header("Location: view-events.php");
                        exit();
                    } else {
                        echo "<div class='error-banner'>Error deleting event. Please try again.</div>";
                    }

                } else {

                    // Show the event details and ask the user to confirm deletion
                    $row = get_event($conn, $event_id);

                    if (!$row) {
                        echo "<div class='error-banner'>Event not found. <a href='view-events.php'>Back to events</a>.</div>";
                    } else {
                    ?>

                    <!-- Confirmation box -->
                    <div class="form-section">
                        <p style="font-family:Arial,sans-serif; font-size:15px; margin-bottom:16px;">
                            Are you sure you want to delete the following event? This cannot be undone.
                        </p>

                        <table>
                            <tr>
                                <td>Event Name</td>
                                <td><?php echo e($row["event_name"]); ?></td>
                            </tr>
                            <tr>
                                <td>Category</td>
                                <td><?php echo e($row["event_category"]); ?></td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td><?php echo e($row["start_date"]); ?></td>
                            </tr>
                            <tr>
                                <td>End Date</td>
                                <td><?php echo e($row["end_date"]); ?></td>
                            </tr>
                        </table>

                        <br>

                        <!-- Confirm delete button -->
                        <form method="POST" action="delete-event.php?event_id=<?php echo $event_id; ?>" style="display:inline;">
                            <input type="hidden" name="confirm" value="yes">
                            <button type="submit"
                               style="display:inline-block; background-color:#cc0000; color:#ffffff; padding:10px 24px; border:none; cursor:pointer; font-family:Arial,sans-serif; font-size:14px; font-weight:bold;">
                                Yes, Delete Event
                            </button>
                        </form>

                        &nbsp;&nbsp;

                        <!-- Cancel button -->
                        <a href="view-events.php" 
                           style="display:inline-block; background-color:#1a1a2e; color:#e8a838; padding:10px 24px; text-decoration:none; font-family:Arial,sans-serif; font-size:14px; font-weight:bold;">
                            Cancel
                        </a>
                    </div>

                    <?php
                    }
                }

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
