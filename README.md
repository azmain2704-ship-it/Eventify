# Eventify

A small web app for advertising and browsing public events. Organisers can **add, view, edit and delete** events, and everything is stored in a MySQL database.

I originally built it for my *SEN4001 Web Design & Databases* module at Cardiff Metropolitan University. Afterwards I hardened it against common web attacks (see [Security](#security)).

![View events page](screenshots/2-view-events.png)

## Features

- **Add events** using a form with name, description, category, keywords, image/video links and start/end dates
- **Two layers of validation:** JavaScript checks run in the browser for quick feedback, and PHP checks run on the server so they can't be bypassed (for example, the end date can't be before the start date)
- **Browse events** in date order, shown as cards
- **Edit** an existing event using a pre-filled form
- **Delete** with a confirmation step

## Built with

| Layer    | Tech                                      |
|----------|-------------------------------------------|
| Frontend | HTML5, CSS3, vanilla JavaScript           |
| Backend  | PHP 8 (mysqli with prepared statements)   |
| Database | MySQL / MariaDB                           |
| Local dev| XAMPP (Apache + MySQL)                    |

## Run it locally

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this folder into `C:\xampp\htdocs\eventify`.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`), go to **Import**, and choose `eventify_db.sql`. This creates the `cmoeventmanagement` database along with some sample events.
4. Visit `http://localhost/eventify/`.

The app connects to MySQL as `root` with no password, which is the XAMPP default. To use a different database or user, set these environment variables instead of editing the code: `EVENTIFY_DB_HOST`, `EVENTIFY_DB_NAME`, `EVENTIFY_DB_USER` and `EVENTIFY_DB_PASS`.

**No XAMPP?** If you have PHP and MySQL installed, run `php -S localhost:8080` in this folder.

## Project structure

```
eventify/
├── index.php          Home page
├── add-event.php      Create (C)
├── view-events.php    Read (R)
├── edit-event.php     Update (U)
├── delete-event.php   Delete (D)
├── about.php
├── db.php             Shared DB connection + security helpers
├── eventify_db.sql    Database schema + sample data
├── css/style.css
├── js/validation.js   Client-side form validation
└── screenshots/
```

## Security

The first version built its SQL queries by pasting form input straight into strings, so it was open to SQL injection. It also printed user input back to the page without escaping it. Here's what I changed:

- **SQL injection.** Every query now uses a **prepared statement** (`mysqli_prepare` with `?` placeholders), so the database always treats user input as data, never as SQL. As a bonus, event names with apostrophes (such as "Fred's Party") no longer break the insert.
- **Cross-site scripting (XSS).** Everything the app outputs goes through `htmlspecialchars()` (the `e()` helper in `db.php`). If someone types `<script>` into a form, it now appears as plain text instead of running in other visitors' browsers.
- **Unsafe links.** Image and video URLs are only displayed if they start with `http` or `https`. This blocks values like `javascript:alert(1)`.
- **Deleting through a link.** Events can only be deleted by a POST request from the confirm button. Before, simply visiting a URL could delete an event.
- **IDs from the URL** are converted to integers, and a "not found" message appears if the event doesn't exist.
- **Database credentials** live in one file (`db.php`) and can be supplied through environment variables, so no real password ever needs to be committed.

**Passwords:** this version has no login system, so it doesn't store any passwords. If I add user accounts, I'll hash passwords with PHP's `password_hash()` and check them with `password_verify()` rather than storing them as plain text.

## Screenshots

| Home | Add event |
|------|-----------|
| ![Home](screenshots/1-home.png) | ![Add event](screenshots/3-add-event-form.png) |

| All events | Delete confirmation |
|------------|---------------------|
| ![Events](screenshots/2-view-events.png) | ![Delete](screenshots/5-delete-confirm.png) |

## What I'd add next

- User accounts with hashed passwords, so organisers can only edit their own events
- Search and filtering by category or date
- CSRF tokens on the forms
- Image uploads instead of URL links
