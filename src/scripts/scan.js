import 'dotenv/config';
import fs from 'fs';
import path from 'path';
import crypto from 'crypto';
import db from '../config/db.js';

const IMAGE_BASE = process.env.IMAGE_BASE_PATH || 'images';

function hashFile(filePath, algo) {
  const h = crypto.createHash(algo);
  h.update(fs.readFileSync(filePath));
  return h.digest('hex');
}

function extractBasicMetadata(filePath) {
  const stats = fs.statSync(filePath);
  return {
    FileSize: stats.size,
    CreatedAt: stats.mtime.toISOString()
  };
}

function upsert(metadata) {
  const cols = Object.keys(metadata);
  const placeholders = cols.map(()=>'?').join(',');
  const sql = `INSERT INTO Metadata(${cols.join(',')}) VALUES(${placeholders})`;
  try {
    db.prepare(sql).run(...cols.map(c=>metadata[c]));
  } catch (e) {
    console.error('Insert failed', e.message);
  }
}

function scanDir(dir) {
  if (!fs.existsSync(dir)) return;
  const entries = fs.readdirSync(dir, { withFileTypes: true });
  for (const entry of entries) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      scanDir(full);
    } else if (entry.isFile() && /\.png$/i.test(entry.name)) {
      const relDir = path.relative(IMAGE_BASE, path.dirname(full));
      const baseName = path.basename(entry.name, path.extname(entry.name));
      const basic = extractBasicMetadata(full);
      const nsfwProb = 0; // placeholder; integrate real classifier if needed
      const sha1 = hashFile(full, 'sha1');
      const sha256 = hashFile(full, 'sha256');
      const md5 = hashFile(full, 'md5');
      upsert({
        FileName: baseName,
        Directory: relDir || '.',
        FileSize: basic.FileSize,
        PositivePrompt: null,
        NegativePrompt: null,
        Steps: null,
        Sampler: null,
        CFGScale: null,
        Seed: null,
        ImageSize: null,
        ModelHash: null,
        Model: null,
        SeedResizeFrom: null,
        DenoisingStrength: null,
        Version: null,
        NSFWProbability: nsfwProb,
        SHA1: sha1,
        SHA256: sha256,
        MD5: md5,
        CreatedAt: basic.CreatedAt
      });
    }
  }
}

console.log('Scanning images in', IMAGE_BASE);
scanDir(IMAGE_BASE);
console.log('Scan complete');
