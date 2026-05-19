import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './PrintMenu.css';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:3000';

const PrintMenu = ({ year = 2026, week = 21, onClose }) => {
  const [menu, setMenu] = useState(null);
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);

  const categories = [
    { key: 'M1', label: 'Vollkost M1', short: 'M1' },
    { key: 'M2', label: 'Leichte Kost M2', short: 'M2' },
    { key: 'M3', label: 'Premium M3', short: 'M3' },
    { key: 'M4', label: 'Tagesmenü M4', short: 'M4' },
    { key: 'D', label: 'Dessert', short: 'D' },
    { key: 'RK', label: 'Rohkost', short: 'RK' },
    { key: 'AE', label: 'Abendessen', short: 'AE' },
    { key: 'S', label: 'Salatteller', short: 'S' }
  ];

  const dayNames = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
  const dayShort = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

  useEffect(() => {
    fetchMenu();
  }, [year, week]);

  const fetchMenu = async () => {
    setLoading(true);
    try {
      const response = await axios.get(`${API_URL}/api/menu/${year}/${week}`);
      setMenu(response.data.plan);
      setItems(response.data.items || []);
    } catch (err) {
      console.error('Error fetching menu:', err);
    } finally {
      setLoading(false);
    }
  };

  const getItemsForDay = (dayIdx, categoryLabel) => {
    return items.filter(
      (item) => item.weekday === dayIdx && item.category === categoryLabel && item.visible
    );
  };

  const handlePrint = () => {
    window.print();
  };

  if (loading) return <div className="print-menu-loading">Lädt...</div>;

  // Berechne Daten für Kopfzeile
  const startDate = new Date(year, 0, 1);
  const d = new Date(startDate);
  d.setDate(d.getDate() + (week - 1) * 7);
  const weekStart = new Date(d);
  weekStart.setDate(weekStart.getDate() - weekStart.getDay() + 1); // Montag

  const weekEnd = new Date(weekStart);
  weekEnd.setDate(weekEnd.getDate() + 6); // Sonntag

  const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
  };

  return (
    <div className="print-menu-modal" onClick={onClose}>
      <div className="print-menu-container" onClick={(e) => e.stopPropagation()}>
        <button className="print-menu-close" onClick={onClose}>×</button>
        <button className="print-menu-button" onClick={handlePrint}>
          🖨️ Drucken
        </button>

        <div className="print-page">
          {/* KOPFZEILE */}
          <div className="print-header">
            <div className="header-left">
              <div className="company-name">BMV-Menüdienst</div>
              <div className="company-address">Am Gutshof 6 · 14542 Werder (Havel)</div>
              <div className="company-phone">Tel. 03327 5745066</div>
            </div>

            <div className="header-center">
              <div className="plan-title">WOCHENSPEISEPLAN KW {week}</div>
              <div className="plan-dates">
                {formatDate(weekStart)} bis {formatDate(weekEnd)}
              </div>
            </div>

            <div className="header-right">
              <div className="order-deadline">
                Bestellung bis:<br />
                <div className="deadline-field">_________________</div>
              </div>
            </div>
          </div>

          {/* HAUPTBEREICH */}
          <div className="print-content">
            {/* LINKE SEITE: SPEISEPLAN */}
            <div className="print-main-table-section">
              <table className="print-main-table">
                <thead>
                  <tr>
                    <th className="day-col">Tag</th>
                    {categories.map((cat) => (
                      <th key={cat.key} className="category-col">
                        {cat.label}
                      </th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {dayNames.map((dayName, dayIdx) => (
                    <tr key={dayIdx} className="day-row">
                      <td className="day-cell-label">{dayName}</td>
                      {categories.map((cat) => {
                        const categoryLabel = cat.label;
                        const dayItems = getItemsForDay(dayIdx, categoryLabel);
                        return (
                          <td key={cat.key} className="dish-cell">
                            {dayItems.length > 0 ? (
                              <div className="dish-list">
                                {dayItems.map((item, idx) => (
                                  <div key={idx} className="dish-item">
                                    <div className="dish-title">{item.title}</div>
                                    {item.allergens && (
                                      <div className="dish-allergens">{item.allergens}</div>
                                    )}
                                    {item.price && (
                                      <div className="dish-price">€ {item.price.toFixed(2)}</div>
                                    )}
                                  </div>
                                ))}
                              </div>
                            ) : (
                              <div className="dish-empty">—</div>
                            )}
                          </td>
                        );
                      })}
                    </tr>
                  ))}
                </tbody>
              </table>

              <div className="allergen-notice">
                * Hinweis: Informationen zu Allergenen und Zusatzstoffen (gemäß LMIV) können in unserer separaten Dokumentation eingesehen oder telefonisch erfragt werden.
              </div>
            </div>

            {/* TRENNLINIE */}
            <div className="print-divider"></div>

            {/* RECHTE SEITE: BESTELLABSCHNITT */}
            <div className="print-order-section">
              {/* KUNDENDATEN */}
              <div className="order-header">
                <div className="order-field">
                  <label>Name:</label>
                  <div className="order-input">_______________________</div>
                </div>
                <div className="order-field">
                  <label>Adresse:</label>
                  <div className="order-input">_______________________</div>
                </div>
                <div className="order-field">
                  <label>Zeitraum:</label>
                  <div className="order-input">_________ bis _________</div>
                </div>
              </div>

              {/* BESTELLMATRIX */}
              <table className="print-order-matrix">
                <thead>
                  <tr>
                    <th className="order-day-header"></th>
                    {categories.map((cat) => (
                      <th key={cat.key} className="order-cat-header">
                        {cat.short}
                      </th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {dayShort.map((day, idx) => (
                    <tr key={idx} className="order-row">
                      <td className="order-day-cell">{day}</td>
                      {categories.map((cat) => (
                        <td key={cat.key} className="order-checkbox-cell"></td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>

              {/* TELEFON */}
              <div className="order-footer">
                <label>Tel:</label>
                <div className="order-input-footer">_______________________</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default PrintMenu;
