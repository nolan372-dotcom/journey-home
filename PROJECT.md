# PROJECT.md — Foster Coordination Tool

*A web app that helps a real foster-based animal rescue track which foster homes are available and where every animal is placed. Built for Take Me Home Pet Rescue (Richardson, TX).*

---

## At a glance

- **What:** A focused web app replacing the spreadsheet-and-group-text chaos a foster-based rescue uses to track foster availability and animal placements.
- **For:** Take Me Home Pet Rescue — a small, all-volunteer, foster-based rescue in the Dallas area.
- **Stack:** PHP 8.1+ · CodeIgniter 4 (MVC) · MySQL · Tailwind CSS · CodeIgniter Shield (auth) · deployed live.
- **Why it matters:** A real-world problem for a real local nonprofit — not a tutorial to-do app. Designed with the rescue's actual workflow in mind.

---

## The problem

Small, foster-based animal rescues don't have a building — their animals live in a network of volunteer foster homes scattered across a city. That makes one question shockingly hard to answer at any given moment: **who is available to foster right now, and where is each animal?**

Today, rescues answer it with a patchwork of spreadsheets, group texts, Facebook messages, emails, and memory. The coordinator becomes a human database. The concrete failures that follow are real and repeating:

- **No single source of truth.** The answer to "who can take a dog this week?" lives in one person's head and a dozen text threads. When that person is unavailable, the rescue stalls.
- **Animals fall through the cracks.** Without a clear "who has which animal" view, it's easy to lose track of an animal's location, double-book a foster, or forget a home is already full.
- **Capacity is invisible.** A foster who can take two cats gets asked to take a fourth, because nobody has a live view of how many animals each home currently holds.
- **Coordination burnout.** The work of chasing this information by text drains the volunteers a small rescue can least afford to lose.

Existing shelter software either doesn't solve this coordination layer well or is priced and built for larger organizations with paid staff and physical shelters — not an all-volunteer foster network.

## Who it's for

The first user and design partner is **Take Me Home Pet Rescue** (Richardson, TX) — a small, foster-based, volunteer-run rescue, exactly the profile that feels this problem most. The primary user is the **foster coordinator**, the person currently holding all of this together by hand. (Support for multiple coordinators with their own logins is a planned future direction — see below.)

## What the app does (V1)

A focused, single-purpose tool with three jobs:

1. **A roster of foster homes** — each with their preferences (species, size, kids/other pets in the home), capacity, and current status (active / paused / inactive).
2. **A roster of animals** — each with their details and status (needs a foster / in foster / adopted).
3. **A "who has which animal" dashboard** — the heart of the app. At a glance: which animals are with which fosters, which animals still need a home, and which fosters are actually available (active and under capacity). Assigning or ending a placement updates everything, and the app won't let a placement exceed a foster's capacity.

That's the whole of V1. It replaces the spreadsheet-and-group-text status quo with one live, shared view.

## How it's built (technical notes)

A few notes on the approach, for anyone reading the code:

- **Data model** — a normalized schema with a join entity (placements) connecting fosters and animals via foreign keys. Placements are ended rather than deleted, so history is preserved.
- **Derived state** — availability and capacity aren't stored fields; they're computed from live placement data, and capacity rules are enforced on write.
- **Structure** — server-rendered MVC (CodeIgniter 4), with CRUD across the foster, animal, and placement resources and form validation on input.
- **Auth** — login and access control via CodeIgniter Shield.
- **Deployment** — runs on a standard PHP + MySQL host, with environment configuration kept out of version control and the document root scoped to the public folder.
- **Requirements** — the schema and features follow the rescue's actual coordination workflow rather than assumed requirements.

## The benefits

**For the coordinator:** one place that answers "who's free?" and "where's this animal?" instantly — less mental load, fewer dropped balls, and the information survives even when they're out sick.

**For the rescue:** faster, more reliable placements; no accidental double-bookings or over-capacity homes; less coordination grind that burns out volunteers. The institutional knowledge lives in the tool, not one person's memory.

**For the fosters:** clearer, more respectful coordination — matched to what they can actually take.

**For the animals:** fewer falling through the cracks, and quicker matching to an available home.

## What it deliberately does NOT do (scope guardrails)

To stay finishable and genuinely useful, V1 leaves these for later: two-way messaging/SMS to fosters, automated foster-to-animal matching, foster applications and orientation tracking, medical/vaccination records, exports to adoption sites, and reporting/analytics. These are good ideas — they're just what gets added *after* the core is finished. Building them upfront is the scope trap.

## Tech stack

PHP 8.1+ with **CodeIgniter 4** (server-rendered MVC), **MySQL** for the database, **Tailwind CSS** for styling, **CodeIgniter Shield** for login, deployed on inexpensive PHP + MySQL hosting. The full step-by-step build plan lives in `ImplementationPlan.md`.

## Milestones & timeline

**Demoable MVP — target: Monday (June 22).** A working, deployed (or locally running) app with realistic seed data: foster and animal rosters, the placement dashboard, and the capacity guard all functioning. This is the engineering artifact suitable to show an employer, and it's entirely in the builder's control.

*Realistic-scope note:* hitting a polished, deployed build by Monday means prioritizing the core (Phases 1–5 in the implementation plan: scaffold → database → fosters → animals → placements/dashboard). Login (Phase 6) and live deploy (Phase 7) are next in priority if time allows; if Monday arrives first, a locally running demo with seed data still tells the whole story.

**Real-world adoption — after Monday, on the rescue's schedule.** Validating the workflow with Take Me Home Pet Rescue and getting a coordinator to use it on real data (Phases 0 and 8) depends on a third party and can't be compressed into the weekend. This is the milestone that turns "an app I built" into "an app a Dallas rescue uses" — worth pursuing, just not deadline-bound.

## Future direction

- **Multiple coordinators**, each with their own login and role-based access (the rescue may have more than one person coordinating).
- The parked **Phase 2 features** above (messaging, matching, applications, medical records, exports, reporting), added only after the core is finished and adopted.

## Status & open questions

Currently in planning, moving toward the Monday demo build. Before the schema is fully locked, a short validation conversation with the rescue (Phase 0) should confirm their real workflow — specifically what "available" means to them in practice, exactly which foster fields matter, and the single most annoying part of their current process. The demo build can proceed on the draft schema in parallel.
