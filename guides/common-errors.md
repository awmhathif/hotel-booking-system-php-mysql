# Common errors and exact fixes

These are the errors people hit most with XAMPP, PHP and MySQL, and the ones this project can show. The numbers in brackets are how many people viewed that question on Stack Overflow. You're not the first.

**Find the real error first.** A blank page hides it. Check `display_errors=On` in `php.ini` (XAMPP → Apache → Config → PHP (php.ini)), restart Apache, refresh. The newest errors are also at the bottom of `C:\xampp\apache\logs\error.log`.

---

## XAMPP won't start

### Apache: "Port 80 in use" or "Apache shutdown unexpectedly" (2.4 million views)
Another program owns port 80. The XAMPP log shows which one ("by PID 4" = Windows itself, usually IIS).
- **Windows IIS:** Windows key → type `services` → open → find **World Wide Web Publishing Service** → right-click → Stop → double-click → Startup type **Disabled** → OK.
- **Skype (old versions):** Skype settings → Advanced → Connection → untick "Use port 80 and 443".
- **Or move Apache:** XAMPP → Apache → Config → `httpd.conf` → find `Listen 80` → change to `Listen 8080` → save → start. Use `http://localhost:8080/hotel/` from now on.

### MySQL: "MySQL shutdown unexpectedly" (3 million views)
Usually after the PC turned off suddenly, or another MySQL is already running.
1. Windows key → `services` → stop any other "MySQL" or "MariaDB" service.
2. Copy `C:\xampp\mysql\data` somewhere as a backup. **Don't delete it.**
3. In `C:\xampp\mysql\data`, rename `aria_log_control` to `aria_log_control.old`. Try to start.
4. Still failing: rename `data` to `data_old`. Make a copy of `C:\xampp\mysql\backup` and call it `data`. Copy your database folder (`hotel_db`) and the file `ibdata1` from `data_old` into the new `data`. Start MySQL.
5. Last resort for this project: import `database.sql` again. Everything comes back with the demo data.

### MySQL: port 3306 in use
Another MySQL program (a MySQL Server install, WAMP, Laragon) is running. Stop it in Windows Services, or change XAMPP's port: XAMPP → MySQL → Config → `my.ini` → change both `port=3306` lines to `3307`. Then in `includes/db.php` use `new mysqli("localhost", "root", "", "hotel_db", 3307);`

---

## The browser shows something wrong

### "Not Found: The requested URL was not found on this server"
- The folder isn't at `htdocs/hotel/`, or the file name is spelled differently. `Rooms.php` and `rooms.php` are different files on Mac and Linux.
- You typed `localhost/hotel/room` without `.php`.

### "Forbidden: You don't have permission to access this resource" (2.7 million views)
You opened a folder with no `index.php` in it, or the project is outside `htdocs`. Open the full page address, for example `http://localhost/hotel/index.php`.

### The page shows PHP code, or the browser downloads the file
You opened it as a file (`file:///C:/xampp/...`) or Apache isn't running. Always use `http://localhost/...`.

### A completely blank white page (143K views)
A fatal PHP error with errors hidden. Turn on `display_errors` (top of this page), or read `apache\logs\error.log`.

### "This site can't be reached / localhost refused to connect"
Apache isn't running. Start it in XAMPP.

### The page has no styling (plain black text, big photos)
`css/style.css` is missing or in the wrong folder. It must be `htdocs/hotel/css/style.css`. Copy it again from `steps/step-01/css/`.

---

## PHP errors

### "Warning: Undefined variable $x" or "Undefined array key 'email'" (2.2 million views)
You used a variable before giving it a value, or read a form box that wasn't sent. This project avoids it with `??`:
```php
$email = $_POST["email"] ?? "";   // "" if the box wasn't sent
```
If you add a new form box, read it the same way.

### "Cannot modify header information - headers already sent by (output started at ...:1)" (2 million views)
`header()` (used by `go()`) and `session_start()` (in `db.php`) must run **before anything is printed**. The message says which file and line printed first. Usually:
- a space or empty line before the PHP start tag at the very top of a file
- an empty line after the PHP end tag at the end of `db.php` or `functions.php` (that's why those two files have no end tag)
- an `echo` you added for testing
- the file was saved "with BOM": VS Code bottom bar → click `UTF-8 with BOM` → Save with Encoding → `UTF-8`.

### "session_start(): Session cannot be started after headers have already been sent"
Same cause as above: `db.php` must be included before any HTML.

### "Fatal error: Call to undefined function e()" (or go(), flash(), require_admin()...)
The page is missing `require "includes/functions.php";` at the top, or you made a typo in the function name, or your `functions.php` is from an earlier step. Compare it with `steps/step-NN/includes/functions.php`.

### "Fatal error: Call to undefined method mysqli::execute_query()"
Your PHP is older than 8.2. Check with `http://localhost/dashboard/phpinfo.php`. Install a newer XAMPP.

### "Fatal error: Uncaught mysqli_sql_exception: ..."
PHP stops on any database error and tells you exactly what MySQL said. Read the part after `mysqli_sql_exception:`:
- **Table 'hotel_db.x' doesn't exist:** you didn't run the SQL of the step you're on. Run it, or import that step's `database.sql` (phpMyAdmin → Import).
- **Unknown column 'x' in 'field list':** the column name in your PHP doesn't match the table, or your database is from an older step. Check the spelling in phpMyAdmin, or import the `database.sql` of your step again.
- **You have an error in your SQL syntax ... near '...':** look just before the quoted text: usually a missing comma, a missing quote, or a word like `ORDER` stuck to the previous word because you joined strings without a space.
- **Column count doesn't match value count:** an `INSERT` has a different number of columns, `?` and values. Count all three.
- **Duplicate entry 'x' for key 'email'** (or `'number'`, `'reference'`): that value already exists (the `UNIQUE` rule).
- **Cannot add or update a child row: a foreign key constraint fails:** you saved something for a user, room type or room that doesn't exist. Most often: you imported the database again while logged in, so your session points at a user that is gone. Log out and in again.
- **Cannot delete or update a parent row:** you tried to delete a room, room type or user that bookings still point to. The Rooms page refuses this with a message; you only see this error if you deleted in phpMyAdmin or changed the code.
- **Data truncated for column 'status'** (or `'role'`, `'method'`): the value isn't one of the words the `ENUM` allows. Check the spelling.
- **Access denied for user 'root'@'localhost'** (581K views): root has a password on this PC; put it in `db.php`.
- **Unknown database 'hotel_db':** import `database.sql`.

### "This form has expired. Go back, refresh the page and try again."
Our CSRF check. The session that held the secret was lost (Apache restarted, cookies cleared, or you opened the form in another browser). Refresh the form page and send again. If it happens on **every** form, a page is missing `require "includes/db.php"`, so `session_start()` never runs.

---

## Things that "don't work" without an error

| Problem | Cause and fix |
|---|---|
| CSS changes don't show | The browser keeps an old copy. Press **Ctrl+F5**. |
| Logged in, but the next page forgets | That page doesn't include `db.php`, so `session_start()` never ran. |
| The demo logins don't work | The `INSERT INTO users` part from step 4 didn't run, or a password hash was cut when you copied (each is exactly 60 characters). Import `steps/step-04/database.sql`. |
| Everything you typed on the site is gone | You imported a `database.sql` again. It always starts fresh with the demo data. |
| The admin logs in and lands on the home page | `login.php` is from before step 8, which sends the admin to the dashboard. |
| A new room type always says "Fully booked" | It has no rooms yet. Add one on Admin → Rooms. |
| Every room says "Fully booked" | Check `rooms_left()`: it must be `check_in < ? AND check_out > ?`, with check-out first in the values. Also: waiting requests hold rooms on purpose. |
| No "Check in" button | The booking isn't confirmed yet (confirm it with a room first), or the arrival day hasn't come: the page says when check-in opens. |
| Check out says the guest still owes money | The bill isn't fully paid. Save the payment first. |
| The guest has no Cancel button | The check-in day has come, the booking is no longer waiting or confirmed, or something is already paid. |
| Uploaded photo is refused | Read the message: over 2 MB, or not a JPG or PNG (iPhone photos are HEIC, and WebP isn't accepted: save as JPG first). |
| A photo under 2 MB still says "Choose a photo" | PHP's own limit is lower than the photo. In `php.ini`, set `upload_max_filesize` and `post_max_size` to at least `8M` and restart Apache. |
| "Check-in can't be in the past" for today | The computer's date or time zone is wrong. The site uses MySQL's date, which comes from the computer. |
| No "Are you sure?", no live search, no total on the room page | `js/app.js` isn't loaded. The pages still work without it (forms send normally). Check the `<script>` line in `footer.php` and press F12 → Console for errors. |
| phpMyAdmin says "Access denied for 'pma'" | XAMPP's phpMyAdmin config got changed. Re-install XAMPP, or reset `phpMyAdmin\config.inc.php` from a fresh XAMPP download. |

---

## Still stuck?

1. Compare your file with the same file in `steps/step-NN/` for the step you're on. VS Code can show differences: right-click your file → **Select for Compare**, right-click the other → **Compare with Selected**.
2. Copy that whole step folder into `htdocs/hotel`, import its `database.sql`, and carry on from the next step. (Importing resets the demo data, so some Run it texts may show slightly different bookings.)
3. Search the exact error message in quotes. Almost every error here has a Stack Overflow answer.
