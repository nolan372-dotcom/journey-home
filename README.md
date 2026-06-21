# Foster Coordination Tool — Take Me Home Pet Rescue

A focused web app that helps **Take Me Home Pet Rescue** (Richardson, TX) answer one question that trips up every foster-based rescue: *who is available to foster right now, and where is each animal?* It replaces the spreadsheet-and-group-text workflow with a single live view: a roster of foster homes, a roster of animals, and a placement dashboard that shows who has which animal, blocks over-capacity placements, and updates instantly when a placement starts or ends.

**Stack:** PHP 8.2 · CodeIgniter 4 · MySQL · Tailwind CSS (CDN) · CodeIgniter Shield

---

## Features

- **Dashboard** — active placements grouped by foster home, animals needing placement with one-click assign, and available foster homes with open slot counts
- **Animals** — full CRUD with searchable/filterable list (name, breed, species, size, status, intake date range); dog-specific weight-labeled size options; archive animals to remove them from active flow without deleting records
- **Foster Homes** — full CRUD with searchable/filterable list (name, email, phone, species accepted, status); capacity tracked live from active placements
- **Placements** — assign an animal to a foster home with capacity guard; end a placement and mark the animal as needing a new foster or adopted; foster assignment page includes search and pagination
- **Users** — admin-only page to add login accounts and activate/deactivate users; prevents self-deactivation
- **Smart navigation** — Cancel buttons on edit pages return to the correct previous page (list vs. detail view)

---

## Prerequisites

| Requirement | Minimum | Notes |
|---|---|---|
| PHP | 8.1+ | Extensions: `intl`, `mbstring`, `mysqlnd`, `curl`, `zip` |
| MySQL / MariaDB | 8.0 / 10.4+ | |
| Composer | 2.x | `composer -V` to verify |

**XAMPP (Windows):** Start Apache and MySQL from the XAMPP Control Panel before running any commands. PHP extensions `intl` and `zip` are disabled by default — see the Windows note below.

---

## Installation

### 1. Get the code

```bash
git clone <repo-url> foster-coordinator
cd foster-coordinator
```

Or download and extract the ZIP, then `cd` into the folder.

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create the environment file

```bash
# Mac / Linux
cp env .env

# Windows (Command Prompt)
copy env .env
```

Open `.env` and set these values:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = foster_coordinator
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Create the database

Connect to MySQL and run:

```sql
CREATE DATABASE foster_coordinator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Via command line: `mysql -u root -e "CREATE DATABASE foster_coordinator;"`

### 5. Run all migrations

The `--all` flag is required to include Shield's auth tables alongside the app tables:

```bash
php spark migrate --all
```

This creates: `foster_homes`, `animals`, `placements`, `users`, `auth_identities`, `auth_logins`, `auth_remember_tokens`, `auth_groups_users`, `auth_permissions_users`, and two settings tables.

### 6. Create the admin account

```bash
php spark db:seed CoordinatorSeeder
```

This creates the initial login account (see [Login credentials](#login-credentials) below). Additional accounts can be added later from the **Users** page inside the app.

### 7. (Optional) Load demo data

```bash
php spark db:seed DemoSeeder
```

Seeds 3 foster homes, 4 animals, and 2 active placements so the dashboard has something to show on first load.

### 8. Start the development server

```bash
php spark serve
```

Open `http://localhost:8080` — you'll be redirected to the login page.

---

## Login credentials

| Field | Value |
|---|---|
| Email | `admin@journeyhome.com` |
| Password | `JourneyHome!` |

**To change your password**, connect to MySQL and run:

```sql
-- Generate a bcrypt hash of your new password first (e.g. at bcrypt-generator.com or via PHP)
UPDATE auth_identities
SET secret2 = '<bcrypt-hash-of-new-password>'
WHERE secret = 'admin@journeyhome.com';
```

**To add more users**, log in and go to **Users** in the nav bar.

---

## Windows-specific notes

### PHP extensions (`php.ini`)

Open `D:\Xampp\php\php.ini` (adjust path for your XAMPP install) and uncomment:

```ini
extension=intl
extension=zip
```

Restart Apache/PHP after saving.

### Session save path

CodeIgniter's file-based sessions need a writable directory. The OneDrive-synced project folder is often not writable by PHP. `app/Config/Session.php` has the save path hardcoded:

```php
public string $savePath = 'C:/Users/nolan/AppData/Local/Temp';
```

**If you're setting this up on a different machine**, change this to any writable absolute path on your system, for example:

- `C:/Users/<YourUsername>/AppData/Local/Temp`
- `C:/Windows/Temp` (if PHP has write access)
- An absolute path outside your OneDrive folder

---

## Project structure

```
foster-coordinator/
├── app/
│   ├── Config/
│   │   ├── Auth.php          # Shield config (registration off, magic links off)
│   │   ├── Filters.php       # session + csrf applied globally
│   │   ├── Routes.php        # app routes + Shield auth routes
│   │   └── Session.php       # session save path (update for your machine)
│   ├── Controllers/
│   │   ├── Home.php          # dashboard
│   │   ├── Fosters.php       # foster home CRUD + search
│   │   ├── Animals.php       # animal CRUD + search + archive
│   │   ├── Placements.php    # place / end placement
│   │   └── Users.php         # user management (add, activate/deactivate)
│   ├── Database/
│   │   ├── Migrations/       # foster_homes, animals, placements, archived column
│   │   └── Seeds/
│   │       ├── DemoSeeder.php         # sample data
│   │       └── CoordinatorSeeder.php  # initial admin account
│   ├── Models/
│   │   ├── FosterHome.php
│   │   ├── Animal.php
│   │   └── Placement.php     # includes activeCount() for capacity checks
│   └── Views/
│       ├── layouts/main.php  # shared layout (Outfit font, emerald/stone palette)
│       ├── auth/login.php    # custom styled login page
│       ├── home/             # dashboard
│       ├── fosters/          # list, detail, create, edit
│       ├── animals/          # list, detail, create, edit
│       ├── placements/       # foster assignment page (search + pagination)
│       └── users/            # user list, add user form
└── public/                   # web root (point your server here)
```

---

## Key design decisions

- **Availability is derived, never stored.** A foster is "available" if `status = active` AND `count(active placements) < max_capacity`. The `Placement::activeCount()` method computes this live.
- **Capacity guard on placement.** The `Placements::create()` action re-checks capacity server-side before inserting, preventing race conditions from double-clicking.
- **Archive over delete.** Animals are archived (soft-flagged) rather than deleted. Archiving ends any active placement and removes the animal from all lists, but the record is preserved and still accessible via direct URL.
- **Server-side search.** All filtering (animals, fosters, placement assignment) is done via GET parameters so results are bookmarkable and work without JavaScript. The intake date range picker (Flatpickr) is the only JS enhancement, loaded only on the Animals list page.
- **User management in-app.** Accounts are managed through the Users page rather than CLI seeders or raw SQL. `$allowRegistration = false` in `app/Config/Auth.php` keeps public sign-up disabled.
- **Tailwind via CDN.** Using the Play CDN for development. Before deploying, install the Tailwind standalone CLI, build `public/css/app.css`, and swap the CDN `<script>` tag for a `<link>` to the compiled file.
