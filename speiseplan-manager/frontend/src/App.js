import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './App.css';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:5000';

const App = () => {
  const [view, setView] = useState('dishes'); // 'dishes' oder 'planner'
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [dishes, setDishes] = useState([]);
  const [newDish, setNewDish] = useState({ category_id: null, name: '', price: 0, allergens: '' });
  const [loading, setLoading] = useState(false);

  // Wochenplaner
  const [year, setYear] = useState(new Date().getFullYear());
  const [week, setWeek] = useState(Math.ceil((new Date().getDate() + new Date(new Date().getFullYear(), 0, 1).getDay()) / 7));
  const [weeklyPlan, setWeeklyPlan] = useState(null);
  const [planItems, setPlanItems] = useState({});

  // Load categories
  useEffect(() => {
    loadCategories();
  }, []);

  const loadCategories = async () => {
    try {
      const response = await axios.get(`${API_URL}/api/categories`);
      setCategories(response.data);
      if (response.data.length > 0) {
        setSelectedCategory(response.data[0].id);
      }
    } catch (error) {
      console.error('Error loading categories:', error);
      alert('Fehler beim Laden der Kategorien');
    }
  };

  // Load dishes
  useEffect(() => {
    if (selectedCategory) {
      loadDishes(selectedCategory);
    }
  }, [selectedCategory]);

  const loadDishes = async (categoryId) => {
    try {
      const response = await axios.get(`${API_URL}/api/dishes?category_id=${categoryId}`);
      setDishes(response.data);
    } catch (error) {
      console.error('Error loading dishes:', error);
    }
  };

  // Load weekly plan
  useEffect(() => {
    if (view === 'planner') {
      loadWeeklyPlan();
    }
  }, [view, year, week]);

  const loadWeeklyPlan = async () => {
    try {
      const response = await axios.get(`${API_URL}/api/weekly-plans/${year}/${week}`);
      setWeeklyPlan(response.data);
      
      // Build plan items map
      const itemsMap = {};
      response.data.items.forEach(item => {
        const key = `${item.weekday}_${item.category_id}`;
        itemsMap[key] = item.dish_id;
      });
      setPlanItems(itemsMap);
    } catch (error) {
      console.error('Error loading weekly plan:', error);
    }
  };

  // Add new dish
  const handleAddDish = async (e) => {
    e.preventDefault();
    if (!newDish.name || !selectedCategory) {
      alert('Bitte alle Felder ausfüllen');
      return;
    }

    try {
      const category = categories.find(c => c.id === selectedCategory);
      const price = newDish.price || category.default_price;

      await axios.post(`${API_URL}/api/dishes`, {
        category_id: selectedCategory,
        name: newDish.name,
        price: parseFloat(price),
        allergens: newDish.allergens
      });

      setNewDish({ category_id: null, name: '', price: 0, allergens: '' });
      loadDishes(selectedCategory);
      alert('Gericht hinzugefügt!');
    } catch (error) {
      console.error('Error adding dish:', error);
      alert('Fehler beim Hinzufügen des Gerichts');
    }
  };

  // Update dish
  const handleUpdateDish = async (dishId, field, value) => {
    const dish = dishes.find(d => d.id === dishId);
    if (!dish) return;

    const updated = { ...dish, [field]: value };
    try {
      await axios.put(`${API_URL}/api/dishes/${dishId}`, updated);
      loadDishes(selectedCategory);
    } catch (error) {
      console.error('Error updating dish:', error);
    }
  };

  // Delete dish
  const handleDeleteDish = async (dishId) => {
    if (window.confirm('Gericht wirklich löschen?')) {
      try {
        await axios.delete(`${API_URL}/api/dishes/${dishId}`);
        loadDishes(selectedCategory);
      } catch (error) {
        console.error('Error deleting dish:', error);
      }
    }
  };

  // Update weekly plan item
  const handleSelectDish = async (weekday, categoryId, dishId) => {
    const key = `${weekday}_${categoryId}`;
    const newItems = { ...planItems, [key]: dishId };
    setPlanItems(newItems);

    // Build items array
    const items = [];
    Object.entries(newItems).forEach(([key, dishId]) => {
      if (dishId) {
        const [weekday, categoryId] = key.split('_').map(Number);
        items.push({ weekday, category_id: categoryId, dish_id: dishId });
      }
    });

    try {
      await axios.post(`${API_URL}/api/weekly-plans/${year}/${week}/items`, { items });
      loadWeeklyPlan();
    } catch (error) {
      console.error('Error updating plan:', error);
    }
  };

  // Get weekday label
  const getWeekdayLabel = (weekday) => {
    const labels = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
    return labels[weekday];
  };

  // Calculate date range for given week (ISO 8601)
  const getWeekDateRange = (year, week) => {
    const jan4 = new Date(year, 0, 4);
    const dayOfWeek = jan4.getDay();
    const weekStart = new Date(jan4);
    weekStart.setDate(jan4.getDate() - dayOfWeek + 1);
    
    const firstMonday = new Date(weekStart);
    firstMonday.setDate(weekStart.getDate() + (week - 1) * 7);
    
    const dates = [];
    for (let i = 0; i < 7; i++) {
      const date = new Date(firstMonday);
      date.setDate(firstMonday.getDate() + i);
      dates.push(date);
    }
    
    return dates;
  };

  // Format date as DD.MM
  const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    return `${day}.${month}`;
  };

  // Get display string for week range
  const getWeekRangeDisplay = () => {
    const dates = getWeekDateRange(year, week);
    const monDate = formatDate(dates[0]);
    const sunDate = formatDate(dates[6]);
    return `KW ${String(week).padStart(2, '0')} (${monDate} - ${sunDate})`;
  };

  return (
    <div className="app">
      <header className="header">
        <h1>📋 Speiseplan Manager</h1>
        <nav className="nav">
          <button 
            className={`nav-btn ${view === 'dishes' ? 'active' : ''}`}
            onClick={() => setView('dishes')}
          >
            Gerichte-Pool
          </button>
          <button 
            className={`nav-btn ${view === 'planner' ? 'active' : ''}`}
            onClick={() => setView('planner')}
          >
            Wochenplaner
          </button>
        </nav>
      </header>

      <main className="main">
        {view === 'dishes' && (
          <div className="dishes-view">
            <div className="category-selector">
              <label>Kategorie:</label>
              <select 
                value={selectedCategory || ''} 
                onChange={(e) => setSelectedCategory(Number(e.target.value))}
              >
                {categories.map(cat => (
                  <option key={cat.id} value={cat.id}>
                    {cat.display_name} (€{cat.default_price.toFixed(2)})
                  </option>
                ))}
              </select>
            </div>

            <div className="add-dish-form">
              <h3>Neues Gericht</h3>
              <form onSubmit={handleAddDish}>
                <input
                  type="text"
                  placeholder="Gerichtname"
                  value={newDish.name}
                  onChange={(e) => setNewDish({ ...newDish, name: e.target.value })}
                />
                <input
                  type="number"
                  placeholder={`Preis (€${categories.find(c => c.id === selectedCategory)?.default_price || '0.00'})`}
                  step="0.10"
                  value={newDish.price || ''}
                  onChange={(e) => setNewDish({ ...newDish, price: parseFloat(e.target.value) || 0 })}
                />
                <input
                  type="text"
                  placeholder="Allergene (z.B. G, L, Ei)"
                  value={newDish.allergens}
                  onChange={(e) => setNewDish({ ...newDish, allergens: e.target.value })}
                />
                <button type="submit">+ Hinzufügen</button>
              </form>
            </div>

            <div className="dishes-list">
              <h3>Gerichte in {categories.find(c => c.id === selectedCategory)?.display_name}</h3>
              <table>
                <thead>
                  <tr>
                    <th>Gericht</th>
                    <th>Preis (€)</th>
                    <th>Allergene</th>
                    <th>Aktionen</th>
                  </tr>
                </thead>
                <tbody>
                  {dishes.map(dish => (
                    <tr key={dish.id}>
                      <td>
                        <input
                          type="text"
                          value={dish.name}
                          onChange={(e) => handleUpdateDish(dish.id, 'name', e.target.value)}
                        />
                      </td>
                      <td>
                        <input
                          type="number"
                          step="0.10"
                          value={dish.price}
                          onChange={(e) => handleUpdateDish(dish.id, 'price', parseFloat(e.target.value))}
                        />
                      </td>
                      <td>
                        <input
                          type="text"
                          value={dish.allergens}
                          onChange={(e) => handleUpdateDish(dish.id, 'allergens', e.target.value)}
                        />
                      </td>
                      <td>
                        <button className="delete-btn" onClick={() => handleDeleteDish(dish.id)}>
                          Löschen
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {view === 'planner' && (
          <div className="planner-view">
            <div className="planner-controls">
              <label>
                Jahr:
                <input type="number" value={year} onChange={(e) => setYear(Number(e.target.value))} />
              </label>
              <label>
                Woche:
                <input type="number" value={week} min="1" max="53" onChange={(e) => setWeek(Number(e.target.value))} />
              </label>
              <div className="week-range-display">
                {getWeekRangeDisplay()}
              </div>
            </div>

            <div className="planner-grid">
              <table>
                <thead>
                  <tr>
                    <th>Kategorie</th>
                    <th>Montag</th>
                    <th>Dienstag</th>
                    <th>Mittwoch</th>
                    <th>Donnerstag</th>
                    <th>Freitag</th>
                    <th style={{ opacity: 0.6 }}>Samstag*</th>
                    <th style={{ opacity: 0.6 }}>Sonntag*</th>
                  </tr>
                </thead>
                <tbody>
                  {categories.map(category => {
                    // Leichte Kost M2 (ID 10) und Premium M3 (ID 11) haben Sa/So
                    const hasWeekend = category.id === 10 || category.id === 11;
                    const weekdays = hasWeekend ? [0, 1, 2, 3, 4, 5, 6] : [0, 1, 2, 3, 4];

                    return (
                      <tr key={category.id}>
                        <td className="category-label">
                          {category.display_name}
                          {hasWeekend && <span style={{ fontSize: '0.8em', color: '#999' }}>*</span>}
                        </td>
                        {weekdays.map(weekday => {
                          const key = `${weekday}_${category.id}`;
                          const dishId = planItems[key];
                          const categoryDishes = dishes.filter(d => d.category_id === category.id);

                          return (
                            <td key={key}>
                              <select
                                value={dishId || ''}
                                onChange={(e) => handleSelectDish(weekday, category.id, Number(e.target.value) || null)}
                              >
                                <option value="">— Wählen —</option>
                                {categoryDishes.map(dish => (
                                  <option key={dish.id} value={dish.id}>
                                    {dish.name} (€{dish.price.toFixed(2)})
                                  </option>
                                ))}
                              </select>
                            </td>
                          );
                        })}
                      </tr>
                    );
                  })}
                </tbody>
              </table>
              <p style={{ fontSize: '0.9em', color: '#666', marginTop: '10px', fontStyle: 'italic' }}>
                * Samstag und Sonntag nur für Leichte Kost M2 und Premium M3
              </p>
            </div>
          </div>
        )}
      </main>
    </div>
  );
};

export default App;
