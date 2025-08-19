import Database from 'better-sqlite3';
import fs from 'fs';
import path from 'path';

const DATA_DIR = process.env.DATA_DIR || 'data';
const DB_FILE = path.join(DATA_DIR, process.env.DB_FILE || 'images.db');

if (!fs.existsSync(DATA_DIR)) fs.mkdirSync(DATA_DIR, { recursive: true });

const db = new Database(DB_FILE);

db.pragma('journal_mode = WAL');
db.pragma('synchronous = NORMAL');

// Initialize schema if not existing
db.exec(`CREATE TABLE IF NOT EXISTS Metadata (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  FileName TEXT,
  Directory TEXT,
  FileSize INTEGER,
  PositivePrompt TEXT,
  NegativePrompt TEXT,
  Steps INTEGER,
  Sampler TEXT,
  CFGScale REAL,
  Seed TEXT,
  ImageSize TEXT,
  ModelHash TEXT,
  Model TEXT,
  SeedResizeFrom TEXT,
  DenoisingStrength REAL,
  Version TEXT,
  NSFWProbability REAL,
  SHA1 TEXT,
  SHA256 TEXT,
  MD5 TEXT,
  CreatedAt TEXT
);`);

// Helpful indexes
db.exec(`CREATE INDEX IF NOT EXISTS idx_metadata_filename ON Metadata(FileName);`);
db.exec(`CREATE INDEX IF NOT EXISTS idx_metadata_model ON Metadata(Model);`);
db.exec(`CREATE INDEX IF NOT EXISTS idx_metadata_nsfw ON Metadata(NSFWProbability);`);

export default db;
