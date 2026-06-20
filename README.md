# Foster Coordination Tool — Take Me Home Pet Rescue

A focused web app that helps **Take Me Home Pet Rescue** (Richardson, TX) answer one question that trips up every foster-based rescue: *who is available to foster right now, and where is each animal?* It replaces the spreadsheet-and-group-text chaos with a single live view: a roster of foster homes, a roster of animals, and a placement dashboard that shows who has which animal, blocks over-capacity placements, and updates instantly when a placement starts or ends.

Built with PHP 8.2 · CodeIgniter 4 · MySQL · Tailwind CSS · CodeIgniter Shield.

## Local setup

```bash
composer install
cp env .env          # Windows: copy env .env
# Edit .env: set CI_ENVIRONMENT=development and app.baseURL
php spark serve      # http://localhost:8080
```
