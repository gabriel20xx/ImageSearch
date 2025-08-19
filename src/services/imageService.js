import db from '../config/db.js';

const VALID_FILTERS = new Set([
  'FileName','Directory','FileSize','PositivePrompt','NegativePrompt','Steps','Sampler','CFGScale','Seed','ImageSize','ModelHash','Model','SeedResizeFrom','DenoisingStrength','Version','NSFWProbability','SHA1','SHA256','MD5','CreatedAt'
]);

export function countAll() {
  return db.prepare('SELECT COUNT(*) as allcount FROM Metadata').get().allcount;
}

export function countFiltered({ filter, comparator, params }) {
  const sql = `SELECT COUNT(*) as count FROM Metadata WHERE ${filter} ${comparator}`;
  return db.prepare(sql).get(...params).count;
}

export function fetchData({ filter, comparator, params, sort, limit, offset }) {
  const sql = `SELECT * FROM Metadata WHERE ${filter} ${comparator} ORDER BY id ${sort} LIMIT ? OFFSET ?`;
  return db.prepare(sql).all(...params, limit, offset);
}

export function validateQuery({ filter, sort }) {
  if (!VALID_FILTERS.has(filter)) throw new Error('Invalid filter');
  if (!['ASC','DESC'].includes(sort)) throw new Error('Invalid sort');
}
