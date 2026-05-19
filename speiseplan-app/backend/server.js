require('dotenv').config();
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const bodyParser = require('body-parser');
const { Pool } = require('pg');

// ════════════════════ EXPRESS & SERVER ════════════════════
const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
  cors: {
    origin: process.env.CORS_ORIGIN?.split(',') || '*',
    methods: ['GET', 'POST', 'PUT', 'DELETE']
  }
});

// ════════════════════ MIDDLEWARE ════════════════════
app.use(cors());
app.use(bodyParser.json({ limit: '50mb' }));
app.use(bodyParser.urlencoded({ limit: '50mb', extended: true }));

// ════════════════════ DATABASE ════════════════════
const pool = new Pool({
  host: process.env.DB_HOST || 'localhost',
  port: process.env.DB_PORT || 5432,
  user: process.env.DB_USER || 'speiseplan',
  password: process.env.DB_PASSWORD || 'speiseplan_secure_pass',
  database: process.env.DB_NAME || 'speiseplan'
});

pool.on('error', (err) => {
  console.error('Unexpected error on idle client', err);
});

// ════════════════════ HEALTH CHECK ════════════════════
app.get('/health', (req, res) => {
  res.json({ status: 'OK', timestamp: new Date().toISOString() });
});

// ════════════════════ API ROUTES ════════════════════

// GET: Speiseplan für Woche
app.get('/api/menu/:year/:week', async (req, res) => {
  try {
    const { year, week } = req.params;
    
    const planResult = await pool.query(
      'SELECT * FROM menu_plans WHERE year = $1 AND week = $2',
      [year, week]
    );

    if (planResult.rows.length === 0) {
      return res.json({ plan: null, items: [] });
    }

    const planId = planResult.rows[0].id;
    const itemsResult = await pool.query(
      'SELECT * FROM menu_items WHERE plan_id = $1 ORDER BY weekday, sort_order',
      [planId]
    );

    res.json({
      plan: planResult.rows[0],
      items: itemsResult.rows
    });
  } catch (err) {
    console.error('Error fetching menu:', err);
    res.status(500).json({ error: err.message });
  }
});

// POST: Speiseplan erstellen oder aktualisieren
app.post('/api/menu', async (req, res) => {
  const client = await pool.connect();
  try {
    await client.query('BEGIN');

    const { year, week, items, published } = req.body;

    // Upsert Plan
    const planResult = await client.query(
      `INSERT INTO menu_plans (year, week, published, created_by, updated_at)
       VALUES ($1, $2, $3, 'api', NOW())
       ON CONFLICT (year, week) DO UPDATE
       SET published = $3, updated_at = NOW()
       RETURNING id`,
      [year, week, published || false]
    );

    const planId = planResult.rows[0].id;

    // Lösche alte Items
    await client.query('DELETE FROM menu_items WHERE plan_id = $1', [planId]);

    // Füge neue Items ein
    if (items && Array.isArray(items)) {
      for (const item of items) {
        await client.query(
          `INSERT INTO menu_items (plan_id, category, weekday, title, description, allergens, price, visible, sort_order)
           VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)`,
          [planId, item.category, item.weekday, item.title, item.description, item.allergens, item.price, item.visible !== false, item.sort_order || 0]
        );
      }
    }

    await client.query('COMMIT');

    // Broadcast Änderung via Socket.IO
    io.emit('menu:updated', { year, week, planId });

    res.json({ success: true, planId });
  } catch (err) {
    await client.query('ROLLBACK');
    console.error('Error saving menu:', err);
    res.status(500).json({ error: err.message });
  } finally {
    client.release();
  }
});

// PUT: Einzelnes Menüitem aktualisieren
app.put('/api/menu-item/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const { title, description, allergens, price, visible } = req.body;

    await pool.query(
      `UPDATE menu_items 
       SET title = $1, description = $2, allergens = $3, price = $4, visible = $5, updated_at = NOW()
       WHERE id = $6`,
      [title, description, allergens, price, visible, id]
    );

    // Holen Plan-Info für Broadcast
    const itemResult = await pool.query('SELECT plan_id FROM menu_items WHERE id = $1', [id]);
    const planResult = await pool.query('SELECT year, week FROM menu_plans WHERE id = $1', [itemResult.rows[0].plan_id]);
    const { year, week } = planResult.rows[0];

    // Broadcast Änderung
    io.emit('menu:updated', { year, week, itemId: id });

    res.json({ success: true });
  } catch (err) {
    console.error('Error updating menu item:', err);
    res.status(500).json({ error: err.message });
  }
});

// DELETE: Menüitem löschen
app.delete('/api/menu-item/:id', async (req, res) => {
  try {
    const { id } = req.params;

    const itemResult = await pool.query('SELECT plan_id FROM menu_items WHERE id = $1', [id]);
    await pool.query('DELETE FROM menu_items WHERE id = $1', [id]);

    const planResult = await pool.query('SELECT year, week FROM menu_plans WHERE id = $1', [itemResult.rows[0].plan_id]);
    const { year, week } = planResult.rows[0];

    io.emit('menu:updated', { year, week });

    res.json({ success: true });
  } catch (err) {
    console.error('Error deleting menu item:', err);
    res.status(500).json({ error: err.message });
  }
});

// POST: Veröffentlichung
app.post('/api/menu/:year/:week/publish', async (req, res) => {
  try {
    const { year, week } = req.params;

    await pool.query(
      `UPDATE menu_plans 
       SET published = true, published_at = NOW(), updated_at = NOW()
       WHERE year = $1 AND week = $2`,
      [year, week]
    );

    io.emit('menu:published', { year, week });

    res.json({ success: true });
  } catch (err) {
    console.error('Error publishing menu:', err);
    res.status(500).json({ error: err.message });
  }
});

// ════════════════════ SOCKET.IO EVENTS ════════════════════
io.on('connection', (socket) => {
  console.log(`Client connected: ${socket.id}`);

  socket.on('subscribe:menu', (data) => {
    socket.join(`menu:${data.year}:${data.week}`);
    console.log(`Client ${socket.id} subscribed to KW ${data.week}/${data.year}`);
  });

  socket.on('disconnect', () => {
    console.log(`Client disconnected: ${socket.id}`);
  });
});

// ════════════════════ START SERVER ════════════════════
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`✓ Speiseplan API running on port ${PORT}`);
  console.log(`✓ Socket.IO ready for real-time updates`);
});
