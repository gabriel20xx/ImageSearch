# ImageSearch (Node.js + SQLite Rewrite)

Rewritten Node.js/Express version of the Horde Image Indexer originally in PHP, now using embedded SQLite (better-sqlite3) and an automatic filesystem scanner.

## Features
- Express + better-sqlite3 (fast, synchronous, zero external server)
- Automatic image directory scan script to populate `Metadata` table
- Clean services & routes separation
- Environment-based configuration (.env)
- Paginated, filterable image search API at `GET /api/images`
- Simple frontend using fetch consuming the API

## Quick Start
1. Copy env example:
```
cp src/config/env.example .env
```
2. Edit `.env` with real credentials.
3. Install dependencies:
```
npm install
```
4. (Optional) Scan images (expects PNG files under `images/`):
```
npm run scan
```

5. Start dev server with auto-reload:
```
npm run dev
```
6. Open: http://localhost:3000

## API
`GET /api/images`
Query params:
- `filter` one of allowed column names
- `search` text (for LIKE filters)
- `model` model name when `filter=Model`
- `sort` ASC | DESC
- `min-max-range` Min | Max | Range (for NSFWProbability)
- `one-value` number
- `lower-value` number
- `upper-value` number
- `count` page size (max 500)
- `page` page number (1-based)

Returns JSON: `{ meta: { totalAll, totalFiltered, page, pageSize, totalPages }, data: [...] }`

## Structure
```
src/
  app.js          # Express app & wiring
  config/
    db.js         # SQLite database init
  routes/
    images.js     # /api/images endpoint
  services/
    imageService.js # DB logic (count/query helpers)
  scripts/
    scan.js       # Populate DB from filesystem
public/
  index.html      # Simple UI
style/            # Existing CSS reused
js/               # Existing fullscreen viewer script reused
```

## Notes
- Add input validation or auth as needed.
- Consider indexing frequently filtered columns in DB.
- Migrate remaining front-end dynamic visibility logic if required.

MIT License
