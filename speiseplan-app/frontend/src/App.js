import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import AdminDashboard from './AdminDashboard';
import PublicMenu from './PublicMenu';
import './App.css';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/admin" element={<AdminDashboard />} />
        <Route path="/speiseplan" element={<PublicMenu />} />
        <Route path="/" element={<PublicMenu year={2026} week={21} />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
