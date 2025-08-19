import 'dotenv/config';
import express from 'express';
import path from 'path';
import imagesRouter from './routes/images.js';

const app = express();

// Basic security headers
app.disable('x-powered-by');

// Static assets
app.use(express.static(path.resolve('public'))); // serves /index.html, /app.js
app.use('/style', express.static(path.resolve('style')));
app.use('/images', express.static(path.resolve('images')));

// API route
app.use('/api/images', imagesRouter);

// Simple frontend (placeholder) serving original index.html converted minimal
// Root is now covered by express.static; keep explicit route as fallback
app.get('/', (req, res) => res.sendFile(path.resolve('public/index.html')));

// Error handler
app.use((err, req, res, next) => { // eslint-disable-line
  console.error(err);
  res.status(400).json({ error: err.message || 'Unexpected error' });
});

const port = process.env.PORT || 3000;
app.listen(port, () => {
  console.log(`Server listening on :${port}`);
});
