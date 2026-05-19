import React, { useState, useEffect } from 'react';
import axios from 'axios';
import io from 'socket.io-client';
import './AdminDashboard.css';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:3000';
const SOCKET_URL = process.env.REACT_APP_SOCKET_URL || 'http://localhost:3000';

const AdminDashboard = () => {
  const [year, setYear] = useState(2026);
  const [week, setWeek] = useState(21);
  const [menu, setMenu] = useState(null);
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(false);
  const [socket, setSocket] = useState(null);

  const categories = [
    'Vollkost M1',
    'Leichte Kost M2',
    'Premium M3',
    'Tagesmenü M4',
    'Dessert',
    'Rohkost',
    'Abendessen',
    'Salatteller'
  ];

  const weekdays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

  // Socket.IO Setup
  useEffect(() => {
    const newSocket = io(SOCKET_URL);
    setSocket(newSocket);

    newSocket.on('menu:updated', (data) => {
      if (data.year === year && data.week === week) {
        fetchMenu(year, week);
      }
    });

    return () => newSocket.close();
  }, [year, week]);

  // Fetch Menu
  const fetchMenu = async (y, w) => {
    setLoading(true);
    try {
      const response = await axios.get(`${API_URL}/api/menu/${y}/${w}`);
      setMenu(response.data.plan);
      setItems(response.data.items || []);
    } catch (err) {
      console.error('Error fetching menu:', err);
      alert('Fehler beim Laden des Menüs');
    } finally {
      setLoading(false);
    }
  };

  // Initial Fetch
  useEffect(() => {
    fetchMenu(year, week);
  }, [year, week]);

  // Save Menu
  const handleSave = async () => {
    try {
      await axios.post(`${API_URL}/api/menu`, {
        year,
        week,
        items,
        published: menu?.published || false
      });
      alert('Menü gespeichert!');
      if (socket) socket.emit('subscribe:menu', { year, week });
    } catch (err) {
      console.error('Error saving menu:', err);
      alert('Fehler beim Speichern');
    }
  };

  // Publish Menu
  const handlePublish = async () => {
    try {
      await axios.post(`${API_URL}/api/menu/${year}/${week}/publish`);
      setMenu({ ...menu, published: true });
      alert('Menü veröffentlicht!');
    } catch (err) {
      console.error('Error publishing:', err);
      alert('Fehler beim Veröffentlichen');
    }
  };

  // Update Item
  const handleUpdateItem = (itemId, field, value) => {
    setItems(
      items.map(item =>
        item.id === itemId ? { ...item, [field]: value } : item
      )
    );
  };

  // Add Item
  const handleAddItem = (category, weekday) => {
    const newItem = {
      id: `new-${Date.now()}`,
      category,
      weekday,
      title: 'Neues Gericht',
      description: '',
      allergens: '',
      price: 0,
      visible: true,
      sort_order: 0
    };
    setItems([...items, newItem]);
  };

  if (loading) return <div className="admin-loading">Lädt...</div>;

  return (
    <div className="admin-dashboard">
      <header className="admin-header">
        <h1>📋 Speiseplan Admin</h1>
        <div className="admin-nav">
          <label>
            Jahr:
            <input type="number" value={year} onChange={(e) => setYear(Number(e.target.value))} />
          </label>
          <label>
            Woche:
            <input type="number" value={week} min="1" max="53" onChange={(e) => setWeek(Number(e.target.value))} />
          </label>
          <span className="publish-status">
            {menu?.published ? '✓ Veröffentlicht' : '○ Entwurf'}
          </span>
        </div>
      </header>

      <div className="admin-controls">
        <button onClick={handleSave} className="btn btn-primary">
          💾 Speichern
        </button>
        <button onClick={handlePublish} className="btn btn-success">
          🚀 Veröffentlichen
        </button>
        <button onClick={() => fetchMenu(year, week)} className="btn btn-secondary">
          🔄 Aktualisieren
        </button>
      </div>

      <div className="menu-grid">
        {categories.map((category) => (
          <div key={category} className="category-section">
            <h3>{category}</h3>
            <div className="weekdays-row">
              {weekdays.map((day, dayIdx) => {
                const categoryItems = items.filter(
                  (item) => item.category === category && item.weekday === dayIdx
                );

                return (
                  <div key={dayIdx} className="day-cell">
                    <div className="day-label">{day}</div>
                    {categoryItems.map((item) => (
                      <div key={item.id} className="menu-item-edit">
                        <input
                          type="text"
                          value={item.title}
                          onChange={(e) => handleUpdateItem(item.id, 'title', e.target.value)}
                          placeholder="Gerichtname"
                          className="item-input"
                        />
                        <input
                          type="text"
                          value={item.allergens || ''}
                          onChange={(e) => handleUpdateItem(item.id, 'allergens', e.target.value)}
                          placeholder="Allergene"
                          className="item-input small"
                        />
                        <input
                          type="number"
                          value={item.price || 0}
                          onChange={(e) => handleUpdateItem(item.id, 'price', parseFloat(e.target.value))}
                          placeholder="Preis"
                          step="0.10"
                          className="item-input small"
                        />
                      </div>
                    ))}
                    <button
                      onClick={() => handleAddItem(category, dayIdx)}
                      className="btn-add-item"
                    >
                      + Hinzufügen
                    </button>
                  </div>
                );
              })}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default AdminDashboard;
