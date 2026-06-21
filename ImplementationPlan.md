# ImplementationPlan.md — Foster Coordination Tool (Take Me Home Pet Rescue)

> Build one phase at a time. Don't start a phase until the previous one's **Verify** check passes. Check off items as you go.
> Scope discipline: V1 is roster + availability + "who has which animal." Messaging and auto-matching are **Phase 2 — out of scope for now** (see bottom).

---

## Assumptions (correct these before building if wrong)

- **Stack:** PHP 8.1+ with **CodeIgniter 4** (MVC), **MySQL 8** (MariaDB is fine), Tailwind CSS for styling, deployed on a cheap PHP host with MySQL. *Rationale: classic, widely-deployed server-rendered stack; CI4 ships migrations, models, form validation, and an official auth library (Shield) out of the box; PHP + MySQL hosting is cheap and everywhere.*
- **Primary user:** one foster coordinator at the rescue. Single-user value from day one; login comes only when it's time to share (Phase 6).
- **The data model below is a draft to confirm with the rescue,** not a guess to build blindly on. Phase 0 validates it.

---

## Tech you'll install (one-time)

- [x] **PHP 8.1+** with the `intl`, `mbstring`, `json`, and `mysqlnd` extensions enabled — verify: `php -v`
- [x] **Composer** (PHP package manager) — verify: `composer -V`
- [x] **MySQL** or **MariaDB** locally — verify you can connect
- [x] **VS Code** + extensions: **PHP Intelephense**, **Tailwind CSS IntelliSense**, **DotENV**, and a MySQL client extension (or use **TablePlus** / **phpMyAdmin** as a separate GUI)
- [x] **Git** — verify: `git --version`
- [x] A free **GitHub** account
- [ ] *Windows tip:* **Laragon** bundles PHP + MySQL + Composer + nice local URLs in one installer — by far the easiest local setup. (Mac: **Herd** or Homebrew; or **XAMPP** on either.)

---

## Phase 0 — Validate before you build

Goal: confirm the real workflow so the schema matches reality.

- [ ] Send the outreach email to Take Me Home Pet Rescue; book a ~20-min call.
- [ ] On the call, confirm: How do they track fosters today? What does "available" actually mean to them? What fields matter (species, size, kids, other pets, capacity)? What's the single most annoying part?
- [ ] Update the **Data Model** section below to match what they tell you.
- **Verify:** You can describe their current process in 3 sentences without guessing.

*(You can start Phase 1 scaffolding in parallel — just don't lock the schema until this is done.)*

---

## Phase 1 — Project scaffold

Goal: a running empty CodeIgniter app in the browser, committed to Git.

- [x] In the VS Code terminal: `composer create-project codeigniter4/appstarter foster-coordinator`
- [x] `cd foster-coordinator`
- [x] Copy the env file: `cp env .env` (Windows: `copy env .env`)
- [x] In `.env`, set `CI_ENVIRONMENT = development` and `app.baseURL = 'http://localhost:8080/'`
- [x] `php spark serve` → open `http://localhost:8080`
- [x] `git init`, first commit *(GitHub push pending — waiting on repo URL)*
- [x] Edit `README.md`: one paragraph on what this is and who it's for.
- **Verify:** The CodeIgniter welcome page loads at localhost:8080 and the repo is on GitHub.

---

## Phase 2 — Database + data model

Goal: a MySQL database that reflects the rescue's real workflow, built with CI4 migrations.

- [x] Create the database: `CREATE DATABASE foster_coordinator;` (via CLI, phpMyAdmin, or TablePlus)
- [x] In `.env`, set the `database.default.*` values: `hostname`, `database = foster_coordinator`, `username`, `password`, and `DBDriver = MySQLi`
- [x] Generate migrations:
  - `php spark make:migration create_foster_homes`
  - `php spark make:migration create_animals`
  - `php spark make:migration create_placements`
- [x] Fill in each migration's `up()` with the columns from the **Draft Data Model** below (add a foreign key from `placements.animal_id` → `animals.id` and `placements.foster_home_id` → `foster_homes.id`)
- [x] Run them: `php spark migrate`
- [x] Generate models: `php spark make:model FosterHome`, `php spark make:model Animal`, `php spark make:model Placement` (set `$allowedFields` and `$useTimestamps = true` in each)
- [x] Generate a seeder: `php spark make:seeder DemoSeeder`; add 3 fake fosters + 4 fake animals + 2 placements; run `php spark db:seed DemoSeeder`
- **Verify:** In phpMyAdmin/TablePlus the three tables exist with seeded rows, and placements correctly reference real fosters and animals.

**Draft Data Model (confirm in Phase 0):**

- **foster_homes:** id, name, email, phone, area_zip, species_accepted (dog/cat/both), size_preference, max_capacity (int), has_kids (bool), has_other_pets (bool), status (active/paused/inactive), notes, created_at, updated_at
- **animals:** id, name, species, breed, age_group, size, intake_date, status (needs_foster/in_foster/adopted), notes, photo_url (nullable), created_at, updated_at
- **placements:** id, animal_id → animals, foster_home_id → foster_homes, start_date, end_date (null = currently placed), notes, created_at, updated_at
- **Availability is derived, not stored:** a foster is "available" if status = active AND (count of placements where end_date IS NULL < max_capacity).

---

## Phase 3 — Foster homes (CRUD)

Goal: add, view, edit, and deactivate foster homes (Controller + Model + Views).

- [x] `php spark make:controller Fosters`
- [x] Add routes in `app/Config/Routes.php` (a resource route, or explicit GET/POST routes)
- [x] Index view `/fosters` — table of all fosters + their current animal count
- [x] "Add foster" form → store action (use CI4 **form validation** on the POST)
- [x] Foster detail page `/fosters/(:num)` — full info + their current animals
- [x] Edit foster → update action
- [x] Set status to paused/inactive (a status update, **not** a hard delete — keep history)
- **Verify:** You can add a foster, see it in the list, edit it, and pause it — data persists after refresh.

---

## Phase 4 — Animals (CRUD)

Goal: same lifecycle for animals.

- [x] `php spark make:controller Animals` + routes
- [x] Index view `/animals` — table with name, species, size, status
- [x] "Add animal" form → store (with validation)
- [x] Animal detail page `/animals/(:num)`
- [x] Edit animal → update
- [x] (Optional, simple) a `photo_url` field rendered as a thumbnail — **skip file uploads** for V1
- **Verify:** You can add an animal, see it, edit it; status changes persist.

---

## Phase 5 — Placements + the dashboard (the heart of the app)

Goal: assign animals to fosters and see "who has which animal" at a glance.

- [x] `php spark make:controller Placements` (or add methods to an existing controller)
- [x] "Place animal with foster" action — pick an available foster for an unplaced animal → inserts a placement, sets animal status to `in_foster`
- [x] "End placement" action — sets `end_date`, sets animal status to `needs_foster` or `adopted`
- [x] **Dashboard at `/`** (a `Home` controller method) with three panels:
  - [x] Fosters and the animals currently with each
  - [x] Animals needing a foster (unplaced)
  - [x] Available fosters (active + under capacity)
- [x] Capacity guard in the place action: block the placement if the foster's active placements already equal `max_capacity` (use the query builder to count)
- **Verify:** Place an animal → it appears under that foster on the dashboard, the foster's available slots drop, and the animal leaves the "needs a foster" list. End the placement → it all reverses.

---

## Phase 6 — Polish + auth (before sharing with the rescue)

Goal: presentable and not publicly open.

- [x] Install the official auth library: `composer require codeigniter4/shield` then `php spark shield:setup`
- [x] Run Shield's migrations and protect your routes with the `session` auth filter (in `Filters.php` globals)
- [x] Create one coordinator login (via `CoordinatorSeeder`; registration disabled in `Auth.php`)
- [ ] Styling: install the **Tailwind standalone CLI**, build `public/css/app.css`, and link it in your layout view *(CDN kept for dev — compile CSS before Phase 7 deploy)*
- [x] Empty states — all three dashboard panels + list views have contextual empty states
- [x] Responsive pass — tables wrapped in `overflow-x-auto`; forms use `sm:` breakpoints
- [x] Quick accessibility pass: all form inputs have matching `id`/`for` pairs; sufficient contrast throughout
- **Verify:** A logged-out visitor is redirected to login and can't see foster data; the app is usable on a phone screen.

---

## Phase 7 — Deploy

Goal: a real URL you can send.

- [ ] **Default route — cheap shared host with cPanel** (e.g., Hostinger, Namecheap):
  - [ ] Create a MySQL database + user in cPanel
  - [ ] Push the code up (Git deploy or SFTP)
  - [ ] **Point the web server's document root to the `/public` folder, not the project root** (CI4 security requirement — the rest of the app must sit above the web root)
  - [ ] Set production `.env`: `CI_ENVIRONMENT = production`, the live DB creds, and the real `app.baseURL`
  - [ ] Run `php spark migrate` over SSH (or a one-time protected migration route if SSH isn't available)
- [ ] **Alternatives:** Railway or Render (provision MySQL, deploy PHP via a Dockerfile/Nixpacks) for a more modern flow; **InfinityFree** for a genuinely free PHP + MySQL option.
- [ ] Smoke-test the live site end to end.
- **Verify:** The live URL works, you can log in, and a placement round-trips on the deployed app.

---

## Phase 8 — Design-partner loop

Goal: turn "an app I built" into "an app a Dallas rescue uses."

- [ ] Walk the rescue through it; watch them use it; note friction
- [ ] Fix the top 1–3 friction points only (resist scope creep)
- [ ] Ask if they'll keep using it / give a one-line testimonial
- [ ] Write the portfolio blurb: problem → who it's for → what you built → that it's in use
- **Verify:** A real coordinator has used it on real (or realistic) data and given feedback.

---

## Phase 2 features — OUT OF SCOPE for V1 (park here, don't build yet)

- Two-way messaging / SMS to fosters
- Automated foster↔animal matching algorithm
- Foster application + orientation tracking
- Medical/vaccination records
- Export to Petfinder / Adopt-a-Pet
- Reporting/analytics

These are real and good — they're just what you add *after* the core is finished and adopted. Building them now is the scope trap.

---

## Definition of done for V1

A coordinator can, from their phone, see who's available to foster, see every animal and where it is, assign and end placements without exceeding capacity — on a deployed, password-protected site — and a real person at Take Me Home Pet Rescue has used it and reacted.
