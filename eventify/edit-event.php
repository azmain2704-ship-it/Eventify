<!DOCTYPE html>
<html>
<head>
    <title>Eventify - Edit Event</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/validation.js"></script>
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

            <h2 class="page-title">Edit Event</h2>

            <?php
            // Shared connection details and helpers (see db.php)
            require_once "db.php";

            // Get the event_id from the URL
            $event_id = (int) ($_GET["event_id"] ?? 0);

            // Variables to hold error messages
            $err_event_name = "";
            $err_event_description = "";
            $err_event_category = "";
            $err_keywords = "";
            $err_start_date = "";
            $err_end_date = "";

            // Connect to the database
            $conn = db_connect();

            // Check connection
            if (!$conn) {
                echo "<div class='error-banner'>Database connection failed. Please try again later.</div>";
            } else {

                // Check if the form has been submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    // Retrieve updated values from the form
                    $event_name = trim($_POST["event_name"] ?? "");
                    $event_description = trim($_POST["event_description"] ?? "");
                    $event_category = trim($_POST["event_category"] ?? "");
                    $keywords = trim($_POST["keywords"] ?? "");
                    $video_url = trim($_POST["video_url"] ?? "");
                    $image_url = trim($_POST["image_url"] ?? "");
                    $start_date = trim($_POST["start_date"] ?? "");
                    $end_date = trim($_POST["end_date"] ?? "");

                    // --- Server-side validation ---

                    if (empty($event_name)) {
                        $err_event_name = "Event name is required.";
                    }

                    if (empty($event_description)) {
                        $err_event_description = "Event description is required.";
                    }

                    if (empty($event_category)) {
                        $err_event_category = "Please select a category.";
                    }

                    if (empty($keywords)) {
                        $err_keywords = "Please enter at least one keyword.";
                    }

                    if (empty($start_date)) {
                        $err_start_date = "Start date is required.";
                    }

                    if (empty($end_date)) {
                        $err_end_date = "End date is required.";
                    } elseif ($end_date < $start_date) {
                        $err_end_date = "End date cannot be before the start date.";
                    }

                    // If no errors, run the UPDATE query
                    if ($err_event_name == "" && $err_event_description == "" && $err_event_category == "" && $err_keywords == "" && $err_start_date == "" && $err_end_date == "") {

                        // Prepared statement (see add-event.php for why)
                        $stmt = mysqli_prepare($conn, "UPDATE event_details SET event_name = ?, event_description = ?, event_category = ?, keywords = ?, video_url = ?, image_url = ?, start_date = ?, end_date = ? WHERE event_id = ?");
                        mysqli_stmt_bind_param($stmt, "ssssssssi", $event_name, $event_description, $event_category, $keywords, $video_url, $image_url, $start_date, $end_date, $event_id);

                        if (mysqli_stmt_execute($stmt)) {
                            // Redirect back to view events after successful update
                            header("Location: view-events.php");
                            exit();
                        } else {
                            echo "<div class='error-banner'>Error updating event. Please try again.</div>";
                        }
                    }

                } else {

                    // Form not submitted yet - load existing event data from the database
                    $row = get_event($conn, $event_id);

                    // Stop here if the event doesn't exist (e.g. someone typed a random id)
                    if (!$row) {
                        mysqli_close($conn);
                        echo "<div class='error-banner'>Event not found. <a href='view-events.php'>Back to events</a>.</div>";
                        echo "</div></main></body></html>";
                        exit();
                    }

                    // Populate variables with existing data to pre-fill the form
                    $event_name = $row["event_name"];
                    $event_description = $row["event_description"];
                    $event_category = $row["event_category"];
                    $keywords = $row["keywords"];
                    $video_url = $row["video_url"];
                    $image_url = $row["image_url"];
                    $start_date = $row["start_date"];
                    $end_date = $row["end_date"];
                }

                mysqli_close($conn);
            }
            ?>

            <!-- Edit Event Form - pre-filled with existing values -->
            <div class="form-section">
                <form method="POST" action="edit-event.php?event_id=<?php echo $event_id; ?>" onsubmit="return validateEventForm(this);" autocomplete="off">

                    <table>
                        <tr>
                            <td>Event Name *</td>
                            <td>
                                <input type="text" name="event_name" maxlength="100" value="<?php echo e($event_name); ?>">
                                <span class="error-msg"><?php echo $err_event_name; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Description *</td>
                            <td>
                                <textarea name="event_description" maxlength="500"><?php echo e($event_description); ?></textarea>
                                <span class="error-msg"><?php echo $err_event_description; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Category *</td>
                            <td>
                                <select name="event_category">
                                    <option value="">-- Select a Category --</option>
                                    <option value="Music" <?php if ($event_category == "Music") echo "selected"; ?>>Music</option>
                                    <option value="Sports" <?php if ($event_category == "Sports") echo "selected"; ?>>Sports</option>
                                    <option value="Arts" <?php if ($event_category == "Arts") echo "selected"; ?>>Arts &amp; Culture</option>
                                    <option value="Food" <?php if ($event_category == "Food") echo "selected"; ?>>Food &amp; Drink</option>
                                    <option value="Community" <?php if ($event_category == "Community") echo "selected"; ?>>Community</option>
                                    <option value="Education" <?php if ($event_category == "Education") echo "selected"; ?>>Education</option>
                                    <option value="Other" <?php if ($event_category == "Other") echo "selected"; ?>>Other</option>
                                </select>
                                <span class="error-msg"><?php echo $err_event_category; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Keywords *</td>
                            <td>
                                <input type="text" name="keywords" maxlength="100" value="<?php echo e($keywords); ?>" placeholder="e.g. outdoor, free, family">
                                <span class="error-msg"><?php echo $err_keywords; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Video URL</td>
                            <td>
                                <input type="text" name="video_url" maxlength="100" value="<?php echo e($video_url); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Image URL</td>
                            <td>
                                <input type="text" name="image_url" maxlength="100" value="<?php echo e($image_url); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Start Date *</td>
                            <td>
                                <input type="date" name="start_date" value="<?php echo e($start_date); ?>">
                                <span class="error-msg"><?php echo $err_start_date; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>End Date *</td>
                            <td>
                                <input type="date" name="end_date" value="<?php echo e($end_date); ?>">
                                <span class="error-msg"><?php echo $err_end_date; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" value="Save Changes">
                                &nbsp;
                                <a href="view-events.php" style="font-family:Arial,sans-serif; font-size:13px; color:#888888;">Cancel</a>
                            </td>
                        </tr>
                    </table>

                </form>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>Eventify &copy; 2025 | SEN4001 Web Design &amp; Databases</p>
        <p>Cardiff Metropolitan University</p>
    </footer>

</body>
</html>
