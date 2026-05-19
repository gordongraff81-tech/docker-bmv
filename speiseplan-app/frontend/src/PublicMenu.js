import React, { useState, useEffect } from 'react';
import axios from 'axios';
import io from 'socket.io-client';
import PrintMenu from './PrintMenu';
import './PublicMenu.css';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:3000';
const SOCKET_URL = process.env.REACT_APP_SOCKET_URL || 'http://localhost:3000';

const PublicMenu = ({ year = 2026, week = 21 }) => {
  const [menu, setMenu] = useState(null);
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(false);
  const [socket, setSocket] = useState(null);
  const [showPrintModal, setShowPrintModal] = useState(false);

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

  const weekdays = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag'];
  const weekdayShort = ['Mo', 'Di', 'Mi', 'Do', 'Fr'];

  // Socket.IO Setup
  useEffect(() => {
    const newSocket = io(SOCKET_URL);
    setSocket(newSocket);

    newSocket.on('menu:updated', (data) => {
      if (data.year === parseInt(year) && data.week === parseInt(week)) {
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
    } finally {
      setLoading(false);
    }
  };

  // Initial Fetch
  useEffect(() => {
    fetchMenu(year, week);
  }, [year, week]);

  // Group items by weekday and category
  const getItemsForDay = (dayIdx, category) => {
    return items.filter(
      (item) => item.weekday === dayIdx && item.category === category && item.visible
    );
  };

  if (loading) return <div className="public-loading">Lädt...</div>;

  if (!menu || items.length === 0) {
    return (
      <div className="public-menu empty">
        <div className="empty-state">
          <h2>Speiseplan wird gerade aktualisiert</h2>
          <p>KW {week} / {year}</p>
        </div>
      </div>
    );
  }

  return (
    <>
      <div className="public-menu">
        <button 
          onClick={() => setShowPrintModal(true)} 
          className="print-button no-print"
          title="Speiseplan als professionelles A4-PDF ausdrucken"
        >
          🖨️ Speiseplan drucken
        </button>

        <div className="menu-header no-print">
          <h1>📋 Speiseplan KW {week} / {year}</h1>
          <p className="menu-subtitle">Frisch gekochte Gerichte täglich</p>
        </div>

        <div className="menu-table-wrapper">
          <table className="menu-table">
            <thead>
              <tr>
                <th className="category-col">Kategorie</th>
                {weekdayShort.map((day, idx) => (
                  <th key={idx} className="weekday-col">{day}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {categories.map((category) => (
                <tr key={category} className="category-row">
                  <td className="category-name">
                    <strong>{category}</strong>
                  </td>
                  {[0, 1, 2, 3, 4].map((dayIdx) => {
                    const dayItems = getItemsForDay(dayIdx, category);
                    return (
                      <td key={dayIdx} className="day-cell">
                        {dayItems.length > 0 ? (
                          <div className="item-stack">
                            {dayItems.map((item, idx) => (
                              <div key={idx} className="menu-item">
                                <div className="item-title">{item.title}</div>
                                <div className="item-details">
                                  <span className="item-price">€ {item.price?.toFixed(2)}</span>
                                  {item.allergens && (
                                    <span className="item-allergens" title="Allergene">
                                      {item.allergens}
                                    </span>
                                  )}
                                </div>
                              </div>
                            ))}
                          </div>
                        ) : (
                          <div className="empty-cell">—</div>
                        )}
                      </td>
                    );
                  })}
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="menu-footer no-print">
          <p>✓ Frisch gekochte Menüs täglich | ✓ Allergene transparent | ✓ Faire Preise</p>
        </div>
      </div>

      {showPrintModal && (
        <PrintMenu 
          year={parseInt(year)} 
          week={parseInt(week)} 
          onClose={() => setShowPrintModal(false)} 
        />
      )}
    </>
  );
};

export default PublicMenu;
