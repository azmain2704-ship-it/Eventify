<!DOCTYPE html>
<html>
<head>
    <title>Eventify - Add Event</title>
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
            <li><a class="active" href="add-event.php">Add Event</a></li>
            <li><a href="view-events.php">View Events</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">

            <h2 class="page-title">Add a New Event</h2>

            <?php
            // Shared connection details and helpers (see db.php)
            require_once "db.php";

            // Variables to hold form values (used to retain data after submission)
            $event_name = "";
            $event_description = "";
            $event_category = "";
            $keywords = "";
            $video_url = "";
            $image_url = "";
            $start_date = "";
            $end_date = "";

            // Variables to hold error messages
            $err_event_name = "";
            $err_event_description = "";
            $err_event_category = "";
            $err_keywords = "";
            $err_start_date = "";
            $err_end_date = "";

            // Track whether form was submitted
            $form_submitted = false;
            $form_success = false;

            // Check if the form has been submitted using POST
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $form_submitted = true;

                // Retrieve form data using $_POST
                $event_name = trim($_POST["event_name"] ?? "");
                $event_description = trim($_POST["event_description"] ?? "");
                $event_category = trim($_POST["event_category"] ?? "");
                $keywords = trim($_POST["keywords"] ?? "");
                $video_url = trim($_POST["video_url"] ?? "");
                $image_url = trim($_POST["image_url"] ?? "");
                $start_date = trim($_POST["start_date"] ?? "");
                $end_date = trim($_POST["end_date"] ?? "");

                // --- Server-side validation ---

                // Check event name is not empty
                if (empty($event_name)) {
                    $err_event_name = "Event name is required.";
                }

                // Check description is not empty
                if (empty($event_description)) {
                    $err_event_description = "Event description is required.";
                }

                // Check category is selected
                if (empty($event_category)) {
                    $err_event_category = "Please select a category.";
                }

                // Check keywords is not empty
                if (empty($keywords)) {
                    $err_keywords = "Please enter at least one keyword.";
                }

                // Check start date is not empty
                if (empty($start_date)) {
                    $err_start_date = "Start date is required.";
                }

                // Check end date is not empty and not before start date
                if (empty($end_date)) {
                    $err_end_date = "End date is required.";
                } elseif ($end_date < $start_date) {
                    $err_end_date = "End date cannot be before the start date.";
                }

                // Only save to database if there are no errors
                if ($err_event_name == "" && $err_event_description == "" && $err_event_category == "" && $err_keywords == "" && $err_start_date == "" && $err_end_date == "") {

                    // Connect to the MySQL database using mysqli
                    $conn = db_connect();

                    // Check if connection was successful
                    if (!$conn) {
                        echo "<div class='error-banner'>Database connection failed. Please try again later.</div>";
                    } else {
                        // Use user_id = 1 as we have a single user system
                        $user_id = 1;

                        // Prepared statement: the ? placeholders are filled in by the database driver,
                        // so user input is always treated as data, never as SQL (stops SQL injection,
                        // and means names like "Fred's Party" no longer break the insert).
                        $stmt = mysqli_prepare($conn, "INSERT INTO event_details (user_id, event_name, event_description, event_category, keywords, video_url, image_url, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        mysqli_stmt_bind_param($stmt, "issssssss", $user_id, $event_name, $event_description, $event_category, $keywords, $video_url, $image_url, $start_date, $end_date);

                        // Execute the query
                        if (mysqli_stmt_execute($stmt)) {
                            $form_success = true;
                            // Clear the form fields after successful submission
                            $event_name = "";
                            $event_description = "";
                            $event_category = "";
                            $keywords = "";
                            $video_url = "";
                            $image_url = "";
                            $start_date = "";
                            $end_date = "";
                        } else {
                            echo "<div class='error-banner'>Error saving event. Please try again.</div>";
                        }
                        mysqli_stmt_close($stmt);

                        // Close the database connection
                        mysqli_close($conn);
                    }
                }
            }

            // Show success message if event was saved
            if ($form_success) {
                echo "<div class='success-msg'>Event added successfully! <a href='view-events.php'>View all events</a>.</div>";
            }
            ?>

            <!-- Add Event Form -->
            <div class="form-section">
                <form method="POST" action="add-event.php" onsubmit="return validateEventForm(this);" autocomplete="off">

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
                                <input type="text" name="video_url" maxlength="100" value="<?php echo e($video_url); ?>" placeholder="e.g. https://youtube.com/...">
                            </td>
                        </tr>
                        <tr>
                            <td>Image URL</td>
                            <td>
                                <input type="text" name="image_url" maxlength="100" value="<?php echo e($image_url); ?>" placeholder="e.g. https://images.example.com/event.jpg">
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
                                <input type="submit" value="Add Event">
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
