import express from 'express';
import { countAll, countFiltered, fetchData, validateQuery } from '../services/imageService.js';

const router = express.Router();

router.get('/', (req, res, next) => {
  try {
    const {
      filter = 'PositivePrompt',
      search = '',
      model = 'URPM',
      sort = 'ASC',
      ['min-max-range']: minMaxRange = 'Min',
      ['one-value']: oneValueRaw = '0',
      ['lower-value']: lowerRaw = '0',
      ['upper-value']: upperRaw = '1',
      count: countRaw = '25',
      page: pageRaw = '1'
    } = req.query;

    validateQuery({ filter, sort });

    const oneValue = parseFloat(oneValueRaw) || 0;
    const lower = parseFloat(lowerRaw) || 0;
    const upper = parseFloat(upperRaw) || 1;
    const limit = Math.min(parseInt(countRaw, 10) || 25, 500);
    const page = Math.max(parseInt(pageRaw, 10) || 1, 1);
    const offset = limit * (page - 1);

    let comparator;
    let params;

    if (filter === 'NSFWProbability') {
      comparator = 'BETWEEN ? AND ?';
      if (minMaxRange === 'Min') {
        params = [oneValue, 1];
      } else if (minMaxRange === 'Max') {
        params = [0, oneValue];
      } else {
        params = [lower, upper];
      }
    } else if (filter === 'Model') {
      comparator = '= ?';
      params = [model];
    } else {
      comparator = 'LIKE ?';
      params = [`%${search}%`];
    }

  const totalAll = countAll();
  const totalFiltered = countFiltered({ filter, comparator, params });
  const rows = fetchData({ filter, comparator, params, sort, limit, offset });

    res.json({
      meta: {
        totalAll,
        totalFiltered,
        page,
        pageSize: limit,
        totalPages: Math.ceil(totalFiltered / limit)
      },
      data: rows.map(r => ({
        ...r,
        imagePath: `${process.env.IMAGE_BASE_PATH || 'images'}/${r.Directory}/${r.FileName}.png`
      }))
    });
  } catch (err) {
    next(err);
  }
});

export default router;
