// Application logic extracted from index.html

// -- Search & Pagination --
async function performSearch(page = 1) {
  const params = new URLSearchParams({
    filter: document.getElementById('filter').value,
    search: document.getElementById('search-input').value,
    model: document.getElementById('model-input').value,
    sort: document.getElementById('sort-input').value,
    'min-max-range': document.getElementById('minmaxrange-input').value,
    'one-value': document.getElementById('oneValueInput').value,
    'lower-value': document.getElementById('lowerValueInput').value,
    'upper-value': document.getElementById('upperValueInput').value,
    count: document.getElementById('count-select').value,
    page
  });
  const res = await fetch('/api/images?' + params.toString());
  const json = await res.json();
  renderResults(json);
}

function renderResults({ meta, data }) {
  const container = document.getElementById('results');
  container.innerHTML = '';
  const row = document.createElement('div');
  row.className = 'row';
  const images = [];
  data.forEach(r => {
    images.push(r.imagePath);
    const col = document.createElement('div');
    col.className = 'col-sm-6 col-md-4 col-lg-2 col-xl-1 mb-4';
    col.innerHTML = `<div class="card" onclick="openFullscreen('${r.imagePath}')">
        <img src="${r.imagePath}" class="card-img-top" alt="Image">
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">${(r.PositivePrompt || '').substring(0, 80)}</li>
            <li class="list-group-item">${(r.NegativePrompt || '').substring(0, 80)}</li>
            <li class="list-group-item">${r.Steps || ''}</li>
            <li class="list-group-item">${r.Model || ''}</li>
            <li class="list-group-item">${r.NSFWProbability || ''}</li>
          </ul>
        </div>
      </div>`;
    row.appendChild(col);
  });
  container.appendChild(row);
  window.jsImages = images;
  buildPagination(meta);
  document.getElementById('total-count').textContent = `Total results: ${meta.totalFiltered} of ${meta.totalAll}`;
}

function buildPagination(meta) {
  const pag = document.getElementById('pagination');
  pag.innerHTML = '';
  const { page, totalPages } = meta;
  function add(label, p, disabled = false, active = false) {
    const li = document.createElement('li');
    li.className = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');
    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.textContent = label;
    a.onclick = (e) => { e.preventDefault(); if (!disabled && !active) performSearch(p); };
    li.appendChild(a); pag.appendChild(li);
  }
  if (page > 1) { add('<<', 1); add('<', page - 1); }
  for (let p = Math.max(1, page - 2); p <= Math.min(totalPages, page + 2); p++) { add(String(p), p, false, p === page); }
  if (page < totalPages) { add('>', page + 1); add('>>', totalPages); }
}

// -- Fullscreen viewer (formerly inline) --
let currentImageIndex = 0;
let autoAdvanceEnabled = false;
let autoAdvanceTimeout;
function openFullscreen(imageSrc) {
  const fullscreenContainer = document.getElementById('fullscreenContainer');
  const fullscreenImage = document.getElementById('fullscreenImage');
  fullscreenImage.src = imageSrc;
  fullscreenContainer.style.display = 'grid';
  if (autoAdvanceEnabled) {
    clearTimeout(autoAdvanceTimeout);
    autoAdvanceTimeout = setTimeout(nextImage, 3000);
  }
}
function closeFullscreen() {
  const fullscreenContainer = document.getElementById('fullscreenContainer');
  fullscreenContainer.style.display = 'none';
  clearTimeout(autoAdvanceTimeout);
}
function prevImage() {
  if (!window.jsImages || window.jsImages.length === 0) return;
  currentImageIndex = (currentImageIndex - 1 + window.jsImages.length) % window.jsImages.length;
  openFullscreen(window.jsImages[currentImageIndex]);
}
function nextImage() {
  if (!window.jsImages || window.jsImages.length === 0) return;
  currentImageIndex = (currentImageIndex + 1) % window.jsImages.length;
  openFullscreen(window.jsImages[currentImageIndex]);
}
function toggleAutoAdvance() {
  autoAdvanceEnabled = !autoAdvanceEnabled;
  const toggleButton = document.getElementById('toggleAutoAdvanceButton');
  toggleButton.textContent = autoAdvanceEnabled ? 'Disable Auto-Advance' : 'Enable Auto-Advance';
  if (autoAdvanceEnabled) {
    clearTimeout(autoAdvanceTimeout);
    autoAdvanceTimeout = setTimeout(nextImage, 3000);
  }
}

// Expose functions for inline HTML handlers
Object.assign(window, { openFullscreen, closeFullscreen, prevImage, nextImage, toggleAutoAdvance });

// Init
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('search-form').addEventListener('submit', (e) => { e.preventDefault(); performSearch(1); });
  performSearch(1);
});
