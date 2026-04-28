<?php
header('Content-Type: text/html; charset=utf-8');
header('X-Frame-Options: DENY');
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BMV Admin – Speiseplan</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --navy:#0B2A5B;--navy2:#1a3f7a;--navy3:#234b90;
  --orange:#D95A00;--orange2:#F06A10;
  --teal:#0F766E;--teal2:#0d9488;
  --green:#16A34A;--red:#DC2626;
  --bg:#F0F4FA;--card:#fff;--border:#D1DCF0;
  --text:#1E293B;--muted:#64748B;--label:#334155;
  --sh:0 1px 3px rgba(11,42,91,.10);--sh2:0 4px 16px rgba(11,42,91,.13);
  --r:8px;--font:'Segoe UI',system-ui,sans-serif;
}
html{font-size:15px;}
body{font-family:var(--font);background:var(--bg);color:var(--text);}

/* ── Login ── */
#login-screen{display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--navy);}
.login-box{background:#fff;border-radius:16px;padding:40px 48px;width:360px;box-shadow:var(--sh2);text-align:center;}
.login-box h1{font-size:1.3rem;color:var(--navy);margin-bottom:4px;}
.login-box p{font-size:.85rem;color:var(--muted);margin-bottom:24px;}
.login-box input{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:var(--r);font-size:1rem;margin-bottom:12px;outline:none;}
.login-box input:focus{border-color:var(--navy);}
.btn-login{width:100%;padding:12px;background:var(--navy);color:#fff;border:none;border-radius:var(--r);font-size:1rem;font-weight:600;cursor:pointer;}
.btn-login:hover{background:var(--navy2);}
.login-error{color:var(--red);font-size:.85rem;margin-top:10px;display:none;}

/* ── App ── */
#app{display:none;min-height:100vh;flex-direction:column;}
#app.visible{display:flex;}

/* ── Topnav ── */
.topnav{background:var(--navy);color:#fff;padding:0 20px;height:54px;display:flex;align-items:center;gap:16px;flex-shrink:0;}
.topnav__brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:1.05rem;}
.topnav__brand svg{fill:#fff;opacity:.8;}
.topnav__brand span{font-weight:400;opacity:.6;font-size:.9rem;}
.topnav__right{margin-left:auto;display:flex;align-items:center;gap:12px;}
.kw-nav{display:flex;align-items:center;gap:4px;}
.kw-btn{background:rgba(255,255,255,.15);border:none;color:#fff;width:28px;height:28px;border-radius:6px;cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;}
.kw-btn:hover{background:rgba(255,255,255,.25);}
.kw-label{background:rgba(255,255,255,.12);border-radius:6px;padding:4px 12px;font-size:.9rem;font-weight:600;cursor:pointer;white-space:nowrap;}
.kw-label:hover{background:rgba(255,255,255,.2);}
.btn-logout{background:rgba(255,255,255,.12);border:none;color:#fff;padding:6px 14px;border-radius:6px;cursor:pointer;font-size:.85rem;}
.btn-logout:hover{background:rgba(255,255,255,.2);}

/* ── System-Tabs ── */
.system-tabs{background:#fff;border-bottom:2px solid var(--border);padding:0 20px;display:flex;gap:4px;flex-shrink:0;}
.sys-tab{padding:12px 20px;border:none;background:none;font-family:var(--font);font-size:.95rem;font-weight:600;color:var(--muted);cursor:pointer;border-bottom:3px solid transparent;margin-bottom:-2px;transition:color .15s,border-color .15s;}
.sys-tab:hover{color:var(--navy);}
.sys-tab.active{color:var(--navy);border-bottom-color:var(--navy);}
.sys-tab.active.teal{color:var(--teal);border-bottom-color:var(--teal);}

/* ── Toolbar ── */
.toolbar{background:#fff;border-bottom:1px solid var(--border);padding:8px 20px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex-shrink:0;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:var(--r);border:none;font-family:var(--font);font-size:.85rem;font-weight:600;cursor:pointer;transition:background .15s,opacity .15s;}
.btn--ghost{background:var(--bg);color:var(--text);}
.btn--ghost:hover{background:#e2e8f0;}
.btn--navy{background:var(--navy);color:#fff;}
.btn--navy:hover{background:var(--navy2);}
.btn--orange{background:var(--orange);color:#fff;}
.btn--orange:hover{background:var(--orange2);}
.btn--save{background:var(--green);color:#fff;}
.btn--save:hover{background:#15803d;}
.btn--teal{background:var(--teal);color:#fff;}
.btn--teal:hover{background:var(--teal2);}
.btn--danger{background:var(--red);color:#fff;}
.btn--danger:hover{background:#b91c1c;}
.toolbar__status{margin-left:auto;}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;font-size:.8rem;font-weight:600;}
.status-badge--saved{background:#dcfce7;color:#166534;}
.status-badge--unsaved{background:#fef9c3;color:#854d0e;}
.status-badge--loading{background:#dbeafe;color:#1e40af;}
.status-badge--error{background:#fee2e2;color:#991b1b;}

/* ── Haupt-Grid ── */
.main{flex:1;overflow:auto;padding:20px;}

/* ── Wochengitter (Tabellen-Style) ── */
.plan-grid{width:100%;border-collapse:collapse;background:#fff;border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.plan-grid th{background:var(--navy);color:#fff;padding:10px 12px;font-size:.85rem;font-weight:600;text-align:center;white-space:nowrap;}
.plan-grid th.row-header{text-align:left;background:var(--navy2);width:160px;}
.plan-grid.teal th{background:var(--teal);}
.plan-grid.teal th.row-header{background:var(--teal2);}
.plan-grid td{border:1px solid var(--border);padding:6px;vertical-align:top;min-width:160px;}
.plan-grid tr:nth-child(even) td{background:#f8faff;}
.plan-grid .row-label{background:#f0f4fa;font-weight:700;font-size:.82rem;color:var(--navy);padding:8px 12px;white-space:nowrap;vertical-align:middle;}
.plan-grid.teal .row-label{color:var(--teal);}

/* ── Gerichts-Zelle ── */
.dish-cell{display:flex;flex-direction:column;gap:4px;min-height:52px;}
.dish-name-wrap{display:flex;gap:4px;align-items:center;}
.dish-name-wrap input{flex:1;padding:5px 8px;border:1.5px solid var(--border);border-radius:6px;font-size:.82rem;font-family:var(--font);min-width:0;}
.dish-name-wrap input:focus{outline:none;border-color:var(--navy);}
.plan-grid.teal .dish-name-wrap input:focus{border-color:var(--teal);}
.dish-alg{font-size:.73rem;color:var(--muted);padding:2px 6px;background:#f0f4fa;border-radius:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;}
.btn-db-sm{background:var(--navy2);color:#fff;border:none;border-radius:5px;padding:4px 7px;cursor:pointer;font-size:.78rem;white-space:nowrap;flex-shrink:0;}
.btn-db-sm:hover{background:var(--navy3);}
.plan-grid.teal .btn-db-sm{background:var(--teal);}
.plan-grid.teal .btn-db-sm:hover{background:var(--teal2);}
.dish-price{display:flex;align-items:center;gap:4px;font-size:.78rem;}
.dish-price label{color:var(--muted);}
.dish-price input{width:68px;padding:3px 6px;border:1px solid var(--border);border-radius:4px;font-size:.78rem;}

/* ── DB-Modal ── */
#db-modal{display:none;position:fixed;inset:0;z-index:9999;background:rgba(11,42,91,.55);backdrop-filter:blur(3px);align-items:center;justify-content:center;}
#db-modal.open{display:flex;}
#db-modal-box{background:#fff;border-radius:12px;box-shadow:var(--sh2);width:min(720px,96vw);max-height:85vh;display:flex;flex-direction:column;overflow:hidden;}
#db-modal-head{padding:14px 20px;background:var(--navy);color:#fff;display:flex;align-items:center;gap:10px;}
#db-modal-head h3{flex:1;font-size:1rem;font-weight:600;}
#db-modal-head button{background:none;border:none;color:#fff;font-size:20px;cursor:pointer;padding:0 4px;}
#db-modal-filters{padding:10px 16px;border-bottom:1px solid var(--border);display:flex;gap:8px;flex-wrap:wrap;align-items:center;}
#db-search{flex:1;min-width:180px;padding:7px 12px;border:1px solid var(--border);border-radius:6px;font-size:.9rem;}
#db-search:focus{outline:none;border-color:var(--navy);}
#db-cat-select{padding:7px 10px;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:#fff;}
#db-count{font-size:.8rem;color:var(--muted);white-space:nowrap;}
#db-list{overflow-y:auto;flex:1;padding:6px 0;}
.db-item{padding:9px 20px;cursor:pointer;display:flex;align-items:baseline;gap:8px;border-bottom:1px solid #f0f4fa;}
.db-item:hover{background:#eef3fb;}
.db-item__name{font-size:.9rem;font-weight:500;}
.db-item__alg{font-size:.75rem;color:var(--muted);}

/* ── Modals ── */
.modal-backdrop{display:none;position:fixed;inset:0;z-index:8888;background:rgba(11,42,91,.45);backdrop-filter:blur(2px);align-items:center;justify-content:center;}
.modal-backdrop.open{display:flex;}
.modal{background:#fff;border-radius:12px;padding:28px 32px;width:min(400px,94vw);box-shadow:var(--sh2);}
.modal h3{font-size:1.05rem;color:var(--navy);margin-bottom:8px;}
.modal p{font-size:.88rem;color:var(--muted);margin-bottom:16px;}
.modal-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:16px;}
.kw-jump-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.inp{width:100%;padding:8px 12px;border:1.5px solid var(--border);border-radius:var(--r);font-size:.9rem;font-family:var(--font);}
.inp:focus{outline:none;border-color:var(--navy);}

/* ── Toast ── */
.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{background:#1e293b;color:#fff;padding:10px 18px;border-radius:8px;font-size:.88rem;font-weight:500;opacity:0;transform:translateY(8px);transition:opacity .25s,transform .25s;pointer-events:none;}
.toast.show{opacity:1;transform:translateY(0);}
.toast--ok{background:#166534;}
.toast--warn{background:#854d0e;}
.toast--err{background:#991b1b;}

/* ── Publish-Bar ── */
.publish-bar{display:flex;align-items:center;gap:12px;padding:10px 20px;background:#f0fdf4;border-bottom:1px solid #bbf7d0;font-size:.85rem;color:#166534;}
.publish-bar.draft{background:#fefce8;border-bottom-color:#fef08a;color:#854d0e;}
.publish-badge{font-weight:700;padding:3px 10px;border-radius:12px;font-size:.78rem;}
.publish-bar .publish-badge{background:#dcfce7;color:#166534;}
.publish-bar.draft .publish-badge{background:#fef9c3;color:#854d0e;}
</style>
</head>
<body>

<!-- ── Login ── -->
<div id="login-screen">
  <div class="login-box">
    <h1>BMV Admin</h1>
    <p>Speiseplan-Verwaltung</p>
    <input type="password" id="login-pw" placeholder="Admin-Passwort"
           onkeydown="if(event.key==='Enter')doLogin()">
    <button class="btn-login" onclick="doLogin()">Anmelden</button>
    <div class="login-error" id="login-error">Falsches Passwort.</div>
  </div>
</div>

<!-- ── App ── -->
<div id="app">

  <nav class="topnav">
    <div class="topnav__brand">
      <svg viewBox="0 0 24 24" width="22" height="22"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
      BMV Menüdienst <span>/ Admin</span>
    </div>
    <div class="topnav__right">
      <div class="kw-nav">
        <button class="kw-btn" onclick="changeKW(-1)">‹</button>
        <span class="kw-label" id="kw-label" onclick="openKWModal()">KW — / —</span>
        <button class="kw-btn" onclick="changeKW(1)">›</button>
      </div>
      <button class="btn-logout" onclick="doLogout()">Abmelden</button>
    </div>
  </nav>

  <!-- System-Tabs -->
  <div class="system-tabs">
    <button class="sys-tab active" id="tab-ear" onclick="switchSystem('essen_auf_raedern')">
      🥡 Essen auf Rädern
    </button>
    <button class="sys-tab teal" id="tab-kantine" onclick="switchSystem('kantine')">
      🏢 Kantine Am Gutshof
    </button>
  </div>

  <!-- Publish-Bar -->
  <div class="publish-bar draft" id="publish-bar">
    <span class="publish-badge" id="publish-badge">Entwurf</span>
    <span id="publish-text">Dieser Plan ist noch nicht veröffentlicht.</span>
    <button class="btn btn--save" id="btn-publish" onclick="togglePublish()" style="margin-left:auto;padding:5px 14px;font-size:.82rem;">Veröffentlichen</button>
  </div>

  <!-- Toolbar -->
  <div class="toolbar">
    <button class="btn btn--ghost" onclick="loadWeek()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
      Laden
    </button>
    <button class="btn btn--ghost" onclick="clearWeek()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
      Leeren
    </button>
    <button class="btn btn--ghost" onclick="copyPrevWeek()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
      Vorwoche kopieren
    </button>
    <button class="btn btn--save" onclick="saveWeek()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
      Speichern  <kbd style="opacity:.6;font-size:.75rem;">Ctrl+S</kbd>
    </button>
    <button class="btn btn--teal" onclick="openDishCrud()">
      🍽️ Gerichte
    </button>
    <div class="toolbar__status">
      <span class="status-badge status-badge--loading" id="status-badge">Laden…</span>
    </div>
  </div>

  <!-- Hauptbereich -->
  <div class="main">
    <div id="plan-container"></div>
  </div>

</div>

<!-- ── DB-Modal ── -->
<div id="db-modal">
  <div id="db-modal-box">
    <div id="db-modal-head">
      <h3 id="db-modal-title">Gericht wählen</h3>
      <button onclick="closeDb()">✕</button>
    </div>
    <div id="db-modal-filters">
      <input type="text" id="db-search" placeholder="Suchen…" oninput="renderDbList()" autocomplete="off">
      <select id="db-cat-select" onchange="renderDbList()"></select>
      <span id="db-count"></span>
    </div>
    <div id="db-list"></div>
  </div>
</div>

<!-- ── KW-Modal ── -->
<div class="modal-backdrop" id="kw-modal">
  <div class="modal">
    <h3>Kalenderwoche wählen</h3>
    <p>Direkt zu einer bestimmten Woche springen.</p>
    <div class="kw-jump-grid">
      <div>
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:4px">Jahr</label>
        <input class="inp" type="number" id="modal-year" min="2020" max="2050">
      </div>
      <div>
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:4px">KW</label>
        <input class="inp" type="number" id="modal-kw" min="1" max="53">
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn btn--ghost" onclick="closeKWModal()">Abbrechen</button>
      <button class="btn btn--navy" onclick="jumpToKW()">Springen</button>
    </div>
  </div>
</div>

<!-- ── Confirm-Modal ── -->
<div class="modal-backdrop" id="confirm-modal">
  <div class="modal">
    <h3 id="confirm-title">Bestätigen</h3>
    <p id="confirm-text"></p>
    <div class="modal-actions">
      <button class="btn btn--ghost" onclick="closeConfirm()">Abbrechen</button>
      <button class="btn btn--danger" id="confirm-ok">Ja</button>
    </div>
  </div>
</div>

<!-- ── Gerichte-CRUD Modal ── -->
<div class="modal-backdrop" id="dish-crud-modal">
  <div class="modal" style="width:min(980px,96vw);max-height:88vh;overflow:auto;padding:22px 24px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
      <h3 style="margin:0;flex:1;">Gerichte verwalten</h3>
      <button class="btn btn--ghost" onclick="closeDishCrud()">Schließen</button>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;align-items:start;">
      <div style="background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">
          <div style="font-weight:700;color:var(--navy);">Neues / Bearbeiten</div>
          <span style="font-size:.8rem;color:var(--muted);" id="dish-form-mode">Neu</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 140px;gap:10px;">
          <div>
            <label style="font-size:.82rem;font-weight:700;color:var(--label);display:block;margin-bottom:4px;">Name *</label>
            <input class="inp" id="dish-name" type="text" placeholder="z. B. Rindsroulade mit Rotkohl">
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:700;color:var(--label);display:block;margin-bottom:4px;">Preis (€) *</label>
            <input class="inp" id="dish-price" type="number" min="0" step="0.01" placeholder="7.50">
          </div>
        </div>

        <div style="display:flex;gap:10px;align-items:flex-end;margin-top:10px;">
          <div style="flex:1;">
            <label style="font-size:.82rem;font-weight:700;color:var(--label);display:block;margin-bottom:4px;">Kategorie *</label>
            <select class="inp" id="dish-category"></select>
          </div>
          <button class="btn btn--ghost" onclick="openAddCategoryModal()">+ Kategorie</button>
        </div>

        <div style="margin-top:10px;">
          <label style="font-size:.82rem;font-weight:700;color:var(--label);display:block;margin-bottom:4px;">Allergene (Tags)</label>
          <input class="inp" id="dish-allergen-input" type="text" placeholder='Enter oder "," zum Hinzufügen'>
          <div id="dish-allergen-tags" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px;"></div>
        </div>

        <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:14px;">
          <button class="btn btn--ghost" onclick="resetDishForm()">Zurücksetzen</button>
          <button class="btn btn--save" onclick="saveDish()">Speichern</button>
        </div>
      </div>

      <div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:10px;">
          <input class="inp" id="dish-search" type="text" placeholder="Suchen…" oninput="renderDishList()" style="flex:1;min-width:220px;">
          <select class="inp" id="dish-filter-category" onchange="renderDishList()" style="min-width:220px;"></select>
          <button class="btn btn--ghost" onclick="refreshDishStore()">Refresh</button>
        </div>
        <div id="dish-list" style="background:#fff;border:1px solid var(--border);border-radius:10px;overflow:hidden;"></div>
      </div>
    </div>
  </div>
</div>

<!-- ── Kategorie hinzufügen Modal ── -->
<div class="modal-backdrop" id="add-category-modal">
  <div class="modal">
    <h3>Neue Kategorie</h3>
    <p style="margin-bottom:10px;">Wird dynamisch gespeichert und steht sofort im Select zur Verfügung.</p>
    <div style="display:grid;gap:10px;">
      <div>
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:4px">System</label>
        <select class="inp" id="cat-system">
          <option value="essen_auf_raedern">Essen auf Rädern</option>
          <option value="kantine">Kantine</option>
        </select>
      </div>
      <div>
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:4px">Name *</label>
        <input class="inp" id="cat-label" type="text" placeholder="z. B. Vegetarisch">
      </div>
      <div>
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:4px">Key (optional)</label>
        <input class="inp" id="cat-key" type="text" placeholder="z. B. vegetarisch">
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn btn--ghost" onclick="closeAddCategoryModal()">Abbrechen</button>
      <button class="btn btn--navy" onclick="createCategory()">Anlegen</button>
    </div>
  </div>
</div>

<div class="toast-wrap" id="toast-wrap"></div>

<script>
// ═══════════════════════════════════════════════════════
// KONFIGURATION
// ═══════════════════════════════════════════════════════
const API_BASE = '/api';

// ═══════════════════════════════════════════════════════
// SYSTEM-DEFINITIONEN
// ═══════════════════════════════════════════════════════
const SYSTEMS = {
  essen_auf_raedern: {
    label: 'Essen auf Rädern',
    color: 'navy',
    categories: [
      { key:'vollkost',     label:'Vollkost M1',    hasPrice:true,  priceDefault:7.50 },
      { key:'leichte_kost', label:'Leichte Kost M2',hasPrice:true,  priceDefault:7.20 },
      { key:'premium',      label:'Premium M3',     hasPrice:true,  priceDefault:9.80 },
      { key:'tagesmenu',    label:'Tagesmenü M4',   hasPrice:true,  priceDefault:6.50 },
      { key:'dessert',      label:'Dessert',        hasPrice:true,  priceDefault:1.80 },
      { key:'rohkost',      label:'Rohkost',        hasPrice:true,  priceDefault:1.80 },
      { key:'abendessen',   label:'Abendessen',     hasPrice:true,  priceDefault:5.50 },
      { key:'salat',        label:'Salatteller',    hasPrice:true,  priceDefault:5.50 },
    ],
    days: ['Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag','Sonntag'],
    weekendCats: ['leichte_kost','premium'],
  },
  kantine: {
    label: 'Kantine Am Gutshof',
    color: 'teal',
    categories: [
      { key:'kantine_menu1', label:'Menü 1', hasPrice:false },
      { key:'kantine_menu2', label:'Menü 2', hasPrice:false },
      { key:'kantine_menu3', label:'Menü 3', hasPrice:false },
    ],
    days: ['Montag','Dienstag','Mittwoch','Donnerstag','Freitag'],
  }
};

const DAY_SHORT = ['Mo','Di','Mi','Do','Fr','Sa','So'];

// ═══════════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════════
let state = {
  system:   'essen_auf_raedern',
  year:     new Date().getFullYear(),
  kw:       isoWeek(new Date()),
  // data[system][dayIndex][catKey] = { name, allergens, price }
  data:     { essen_auf_raedern: {}, kantine: {} },
  published:{ essen_auf_raedern: false, kantine: false },
  modified: false,
  saved:    false,
};

// ═══════════════════════════════════════════════════════
// AUTH
// ═══════════════════════════════════════════════════════
function getAdminKey() {
  return sessionStorage.getItem('bmv_admin_key') || '';
}

function doLogin() {
  const pw = document.getElementById('login-pw').value.trim();
  if (!pw) return;
  sessionStorage.setItem('bmv_admin_key', pw);
  document.getElementById('login-screen').style.display = 'none';
  document.getElementById('app').classList.add('visible');
  updateKWLabel();
  loadWeek();
  loadDishStore().catch(() => {});
}

function doLogout() {
  sessionStorage.removeItem('bmv_admin_key');
  location.reload();
}

// ═══════════════════════════════════════════════════════
// UTILS
// ═══════════════════════════════════════════════════════
function isoWeek(dt) {
  const d = new Date(dt); d.setHours(0,0,0,0);
  d.setDate(d.getDate() + 4 - (d.getDay()||7));
  const y = new Date(d.getFullYear(),0,1);
  return Math.ceil((((d-y)/86400000)+1)/7);
}

function mondayOfKW(year, kw) {
  const jan4 = new Date(year, 0, 4);
  const dow  = jan4.getDay() || 7;
  const fm   = new Date(jan4); fm.setDate(jan4.getDate() - dow + 1);
  const m    = new Date(fm);   m.setDate(fm.getDate() + (kw-1)*7);
  return m;
}

function fmtDate(d) {
  return d.getFullYear() + '-' +
    String(d.getMonth()+1).padStart(2,'0') + '-' +
    String(d.getDate()).padStart(2,'0');
}

function cellId(dayIdx, catKey) {
  return `cell-${dayIdx}-${catKey}`;
}

function setStatus(type, text) {
  const b = document.getElementById('status-badge');
  b.className = 'status-badge status-badge--' + type;
  b.textContent = text;
}

function markModified() {
  if (!state.modified) {
    state.modified = true;
    setStatus('unsaved','Nicht gespeichert');
  }
}

// ═══════════════════════════════════════════════════════
// KW-NAVIGATION
// ═══════════════════════════════════════════════════════
function updateKWLabel() {
  const m = mondayOfKW(state.year, state.kw);
  const f = m.toLocaleDateString('de-DE',{day:'2-digit',month:'2-digit',year:'numeric'});
  document.getElementById('kw-label').textContent = `KW ${state.kw} / ${state.year}`;
}

function changeKW(delta) {
  const m = mondayOfKW(state.year, state.kw);
  m.setDate(m.getDate() + delta*7);
  state.year = m.getFullYear();
  state.kw   = isoWeek(m);
  updateKWLabel();
  // Beide Systeme zurücksetzen
  state.data = { essen_auf_raedern:{}, kantine:{} };
  state.published = { essen_auf_raedern:false, kantine:false };
  state.modified = false;
  state.saved = false;
  loadWeek();
}

function openKWModal() {
  document.getElementById('modal-year').value = state.year;
  document.getElementById('modal-kw').value   = state.kw;
  document.getElementById('kw-modal').classList.add('open');
}
function closeKWModal() {
  document.getElementById('kw-modal').classList.remove('open');
}
function jumpToKW() {
  const y = parseInt(document.getElementById('modal-year').value);
  const k = parseInt(document.getElementById('modal-kw').value);
  if (y >= 2020 && k >= 1 && k <= 53) {
    state.year = y; state.kw = k;
    updateKWLabel();
    state.data = { essen_auf_raedern:{}, kantine:{} };
    state.published = { essen_auf_raedern:false, kantine:false };
    closeKWModal();
    loadWeek();
  }
}

// ═══════════════════════════════════════════════════════
// SYSTEM-SWITCH
// ═══════════════════════════════════════════════════════
function switchSystem(sys) {
  state.system = sys;
  document.getElementById('tab-ear').classList.toggle('active',    sys==='essen_auf_raedern');
  document.getElementById('tab-kantine').classList.toggle('active', sys==='kantine');
  renderPlan();
  updatePublishBar();
}

// ═══════════════════════════════════════════════════════
// RENDER PLAN
// ═══════════════════════════════════════════════════════
function renderPlan() {
  const sys    = SYSTEMS[state.system];
  const isTeal = state.system === 'kantine';
  const monday = mondayOfKW(state.year, state.kw);

  let html = `<table class="plan-grid${isTeal?' teal':''}" cellspacing="0">
    <thead><tr>
      <th class="row-header">Kategorie</th>`;

  sys.days.forEach((day, i) => {
    const d = new Date(monday); d.setDate(monday.getDate()+i);
    const dd = String(d.getDate()).padStart(2,'0');
    const mm = String(d.getMonth()+1).padStart(2,'0');
    html += `<th>${DAY_SHORT[i]}<br><span style="font-weight:400;opacity:.75;font-size:.75rem">${dd}.${mm}.</span></th>`;
  });

  html += `</tr></thead><tbody>`;

  sys.categories.forEach(cat => {
    // Wochenendtage: nur weekendCats anzeigen
    const weekendOnly = sys.weekendCats && !sys.weekendCats.includes(cat.key);

    html += `<tr><td class="row-label">${cat.label}</td>`;
    sys.days.forEach((day, di) => {
      const isWeekend = di >= 5;

      // Kategorie nicht für Wochenende — graue Zelle
      if (isWeekend && weekendOnly) {
        html += `<td style="background:#f5f5f5;text-align:center;color:#ccc;font-size:.75rem;vertical-align:middle">—</td>`;
        return;
      }

      const entry = (state.data[state.system][di] || {})[cat.key] || {};
      const name  = entry.name || '';
      const alg   = entry.allergens || '';
      const price = entry.price !== undefined ? entry.price : cat.priceDefault || '';
      const id    = cellId(di, cat.key);

      html += `<td>
        <div class="dish-cell" id="${id}">
          <div class="dish-name-wrap">
            <input type="text" id="${id}-name" value="${esc(name)}"
                   placeholder="Gericht eintragen…"
                   oninput="updateCell('${id}','name',this.value);markModified()">
            <button class="btn-db-sm" onclick="openDb('${id}','${cat.key}')">📋</button>
          </div>
          <div style="display:flex;gap:4px;align-items:center;flex-wrap:wrap">
            <input type="text" id="${id}-alg" value="${esc(alg)}"
                   placeholder="Allergene"
                   style="flex:1;min-width:80px;padding:3px 6px;border:1px solid var(--border);border-radius:4px;font-size:.75rem"
                   oninput="updateCell('${id}','allergens',this.value);markModified()">
            ${cat.hasPrice ? `
            <div class="dish-price">
              <label>€</label>
              <input type="number" id="${id}-price" value="${price}" step="0.10" min="0"
                     oninput="updateCell('${id}','price',parseFloat(this.value));markModified()">
            </div>` : ''}
          </div>
        </div>
      </td>`;
    });
    html += `</tr>`;
  });

  html += `</tbody></table>`;
  document.getElementById('plan-container').innerHTML = html;
}

function esc(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');
}

function updateCell(id, field, value) {
  // id = cell-{dayIdx}-{catKey}
  const parts  = id.split('-');
  const dayIdx = parseInt(parts[1]);
  const catKey = parts.slice(2).join('-');
  if (!state.data[state.system][dayIdx]) state.data[state.system][dayIdx] = {};
  if (!state.data[state.system][dayIdx][catKey]) state.data[state.system][dayIdx][catKey] = {};
  state.data[state.system][dayIdx][catKey][field] = value;
}

// ═══════════════════════════════════════════════════════
// PUBLISH
// ═══════════════════════════════════════════════════════
function updatePublishBar() {
  const pub = state.published[state.system];
  const bar  = document.getElementById('publish-bar');
  const badge = document.getElementById('publish-badge');
  const text  = document.getElementById('publish-text');
  const btn   = document.getElementById('btn-publish');
  if (pub) {
    bar.classList.remove('draft');
    badge.textContent = 'Veröffentlicht';
    text.textContent  = 'Dieser Plan ist für Kunden sichtbar.';
    btn.textContent   = 'Zurückziehen';
    btn.className     = 'btn btn--ghost';
  } else {
    bar.classList.add('draft');
    badge.textContent = 'Entwurf';
    text.textContent  = 'Dieser Plan ist noch nicht veröffentlicht.';
    btn.textContent   = 'Veröffentlichen';
    btn.className     = 'btn btn--save';
  }
}

function togglePublish() {
  state.published[state.system] = !state.published[state.system];
  updatePublishBar();
  markModified();
  saveWeek();
}

// ═══════════════════════════════════════════════════════
// LOAD / SAVE
// ═══════════════════════════════════════════════════════
async function loadWeek() {
  setStatus('loading','Laden…');
  // Beide Systeme laden
  for (const sys of ['essen_auf_raedern','kantine']) {
    const file = jsonPath(sys, state.year, state.kw);
    try {
      const r = await fetch(file + '?t=' + Date.now());
      if (r.ok) {
        const raw = await r.json();
        state.data[sys]      = raw.data      || {};
        state.published[sys] = raw.published || false;
      } else {
        state.data[sys]      = {};
        state.published[sys] = false;
      }
    } catch(e) {
      state.data[sys] = {};
    }
  }
  state.modified = false;
  state.saved    = true;
  renderPlan();
  updatePublishBar();
  setStatus('saved','Gespeichert');
}

async function saveWeek() {
  setStatus('loading','Speichern…');
  const monday = mondayOfKW(state.year, state.kw);

  try {
    for (const sys of ['essen_auf_raedern','kantine']) {
      const payload = {
        year:      state.year,
        kw:        state.kw,
        system:    sys,
        published: state.published[sys],
        data:      state.data[sys],
        week_start: fmtDate(monday),
      };
      const r = await fetch(API_BASE + '/save_plan.php', {
        method: 'POST',
        headers: {
          'Content-Type':  'application/json',
          'X-Admin-Key':   getAdminKey(),
        },
        body: JSON.stringify(payload),
      });
      const res = await r.json();
      if (!res.success) throw new Error(res.message || 'Fehler beim Speichern');
    }
    state.modified = false;
    state.saved    = true;
    setStatus('saved','Gespeichert');
    toast('Gespeichert ✓', 'ok');
  } catch(e) {
    setStatus('error','Fehler');
    toast('Fehler: ' + e.message, 'err');
  }
}

function jsonPath(sys, year, kw) {
  const kwStr = String(kw).padStart(2,'0');
  return `/data/speiseplaene/${sys}-${year}-KW${kwStr}.json`;
}

async function clearWeek() {
  openConfirm(
    'Woche leeren?',
    `Alle Einträge für KW ${state.kw}/${state.year} im System "${SYSTEMS[state.system].label}" werden gelöscht.`,
    () => {
      state.data[state.system] = {};
      markModified();
      renderPlan();
    }
  );
}

async function copyPrevWeek() {
  const m = mondayOfKW(state.year, state.kw);
  m.setDate(m.getDate()-7);
  const prevYear = m.getFullYear();
  const prevKW   = isoWeek(m);
  const file = jsonPath(state.system, prevYear, prevKW);
  setStatus('loading','Vorwoche laden…');
  try {
    const r = await fetch(file);
    if (!r.ok) throw new Error('Keine Daten für Vorwoche');
    const raw = await r.json();
    state.data[state.system] = raw.data || {};
    markModified();
    renderPlan();
    toast(`Vorwoche KW ${prevKW} kopiert`, 'warn');
    setStatus('unsaved','Nicht gespeichert');
  } catch(e) {
    toast('Keine Daten in der Vorwoche', 'err');
    setStatus(state.saved?'saved':'unsaved', state.saved?'Gespeichert':'Nicht gespeichert');
  }
}

// ═══════════════════════════════════════════════════════
// GERICHTEDATENBANK
// ═══════════════════════════════════════════════════════
let DISH_STORE = null;
let dbTarget = null; // { cellId, catKey }

function apiHeaders() {
  return {
    'Content-Type': 'application/json',
    'X-Admin-Key':  getAdminKey(),
  };
}

async function fetchJson(url, opts) {
  const r = await fetch(url, opts);
  const data = await r.json().catch(() => null);
  if (!r.ok) {
    const msg = (data && data.message) ? data.message : ('HTTP ' + r.status);
    throw new Error(msg);
  }
  return data;
}

function allergensToText(arr) {
  if (!arr) return '';
  if (Array.isArray(arr)) return arr.filter(Boolean).join(', ');
  return String(arr || '');
}

async function loadDishStore(force=false) {
  if (DISH_STORE && !force) return DISH_STORE;
  const data = await fetchJson(API_BASE + '/dishes.php', { method: 'GET' });
  const categories = data.categories || [];
  const dishes     = data.dishes || [];

  const catById = {};
  const catBySystemKey = {}; // `${system}::${key}` -> category
  categories.forEach(c => {
    if (c && c.id) catById[c.id] = c;
    if (c && c.system && c.key) catBySystemKey[c.system + '::' + c.key] = c;
  });

  DISH_STORE = { dishes, categories, catById, catBySystemKey, updatedAt: data.updatedAt || null };
  return DISH_STORE;
}

async function openDb(cellId, catKey) {
  dbTarget = { cellId, catKey };
  const store = await loadDishStore();

  const sel = document.getElementById('db-cat-select');
  sel.innerHTML = '';

  const catsForSystem = store.categories
    .filter(c => c.system === state.system)
    .sort((a,b) => (a.label||'').localeCompare((b.label||''), 'de'));

  const defaultCat = store.catBySystemKey[state.system + '::' + catKey] || catsForSystem[0] || null;
  const defaultCatId = defaultCat ? defaultCat.id : '';

  catsForSystem.forEach(cat => {
    const count = store.dishes.filter(d => d.category === cat.id).length;
    const opt = document.createElement('option');
    opt.value = cat.id;
    opt.textContent = (cat.label || cat.key) + ' (' + count + ')';
    if (cat.id === defaultCatId) opt.selected = true;
    sel.appendChild(opt);
  });

  document.getElementById('db-search').value = '';
  document.getElementById('db-modal-title').textContent =
    'Gericht wählen – ' + (defaultCat ? (defaultCat.label || defaultCat.key) : catKey);
  document.getElementById('db-modal').classList.add('open');
  renderDbList();
  document.getElementById('db-search').focus();
}

function closeDb() {
  document.getElementById('db-modal').classList.remove('open');
}

function renderDbList() {
  if (!DISH_STORE) return;
  const categoryId = document.getElementById('db-cat-select').value;
  const query  = document.getElementById('db-search').value.toLowerCase().trim();

  const items = (DISH_STORE.dishes || []).filter(d => {
    if (categoryId && d.category !== categoryId) return false;
    if (query && !(String(d.name||'').toLowerCase().includes(query))) return false;
    return true;
  });

  document.getElementById('db-count').textContent = items.length + ' Gerichte';
  const list = document.getElementById('db-list');
  list.innerHTML = '';

  items.forEach(item => {
    const div = document.createElement('div');
    div.className = 'db-item';
    const algText = allergensToText(item.allergens);
    div.innerHTML = `<span class="db-item__name">${esc(item.name)}</span>` +
      (algText ? `<span class="db-item__alg">(${esc(algText)})</span>` : '');
    div.onclick = () => selectDbItem(item);
    list.appendChild(div);
  });

  if (items.length === 0) {
    list.innerHTML = '<div style="padding:20px;text-align:center;color:var(--muted)">Keine Gerichte gefunden</div>';
  }
}

function selectDbItem(item) {
  if (!dbTarget) return;
  const { cellId } = dbTarget;

  const nameEl = document.getElementById(cellId + '-name');
  if (nameEl) nameEl.value = item.name;

  const algEl = document.getElementById(cellId + '-alg');
  const algText = allergensToText(item.allergens);
  if (algEl) algEl.value = algText;

  const parts  = cellId.split('-');
  const dayIdx = parseInt(parts[1]);
  const catKey = parts.slice(2).join('-');
  if (!state.data[state.system][dayIdx]) state.data[state.system][dayIdx] = {};
  if (!state.data[state.system][dayIdx][catKey]) state.data[state.system][dayIdx][catKey] = {};
  state.data[state.system][dayIdx][catKey].name      = item.name;
  state.data[state.system][dayIdx][catKey].allergens = algText || '';

  markModified();
  closeDb();
}

// ═══════════════════════════════════════════════════════
// DISH CRUD UI
// ═══════════════════════════════════════════════════════
let dishForm = {
  mode: 'create',     // create | edit
  editingId: null,
  allergens: [],
};

function openDishCrud() {
  document.getElementById('dish-crud-modal').classList.add('open');
  resetDishForm();
  refreshDishStore();
  const sysSel = document.getElementById('cat-system');
  if (sysSel) sysSel.value = state.system;
  const allergenInput = document.getElementById('dish-allergen-input');
  if (allergenInput) allergenInput.focus();
}

function closeDishCrud() {
  document.getElementById('dish-crud-modal').classList.remove('open');
}

function refreshDishStore() {
  loadDishStore(true)
    .then(() => {
      hydrateDishCategorySelects();
      renderDishList();
    })
    .catch(e => toast('Fehler: ' + e.message, 'err'));
}

function hydrateDishCategorySelects() {
  const store = DISH_STORE;
  if (!store) return;

  const catsForSystem = store.categories
    .filter(c => c.system === state.system)
    .sort((a,b) => (a.label||'').localeCompare((b.label||''), 'de'));

  const sel = document.getElementById('dish-category');
  const filterSel = document.getElementById('dish-filter-category');

  if (sel) {
    sel.innerHTML = '<option value="">Bitte wählen…</option>';
    catsForSystem.forEach(cat => {
      const opt = document.createElement('option');
      opt.value = cat.id;
      opt.textContent = cat.label || cat.key;
      sel.appendChild(opt);
    });
  }

  if (filterSel) {
    filterSel.innerHTML = '<option value="">Alle Kategorien</option>';
    catsForSystem.forEach(cat => {
      const count = store.dishes.filter(d => d.category === cat.id).length;
      const opt = document.createElement('option');
      opt.value = cat.id;
      opt.textContent = (cat.label || cat.key) + ' (' + count + ')';
      filterSel.appendChild(opt);
    });
  }
}

function renderDishList() {
  const store = DISH_STORE;
  if (!store) return;

  const q = (document.getElementById('dish-search')?.value || '').toLowerCase().trim();
  const catId = document.getElementById('dish-filter-category')?.value || '';

  let items = store.dishes.slice();
  if (catId) items = items.filter(d => d.category === catId);
  if (q) items = items.filter(d => String(d.name||'').toLowerCase().includes(q));

  // Neueste zuerst
  items.sort((a,b) => String(b.updatedAt||'').localeCompare(String(a.updatedAt||'')));

  const list = document.getElementById('dish-list');
  if (!list) return;
  list.innerHTML = '';

  if (items.length === 0) {
    list.innerHTML = '<div style="padding:14px;color:var(--muted);text-align:center;">Keine Gerichte gefunden</div>';
    return;
  }

  items.forEach(d => {
    const cat = store.catById[d.category] || {};
    const alg = allergensToText(d.allergens);
    const row = document.createElement('div');
    row.style.cssText = 'display:flex;gap:10px;align-items:flex-start;padding:12px 14px;border-bottom:1px solid #f0f4fa;';
    row.innerHTML = `
      <div style="flex:1;min-width:0;">
        <div style="font-weight:700;color:var(--navy);line-height:1.25;">${esc(d.name)}</div>
        <div style="font-size:.78rem;color:var(--muted);margin-top:2px;">
          ${(cat.label || cat.key || '—')} · € ${Number(d.price||0).toFixed(2)}
          ${alg ? ' · Allergene: ' + esc(alg) : ''}
        </div>
      </div>
      <div style="display:flex;gap:6px;flex-shrink:0;">
        <button class="btn btn--ghost" style="padding:6px 10px;font-size:.8rem;" onclick="editDish('${d.id}')">Bearbeiten</button>
        <button class="btn btn--danger" style="padding:6px 10px;font-size:.8rem;" onclick="deleteDish('${d.id}')">Löschen</button>
      </div>
    `;
    list.appendChild(row);
  });
}

function resetDishForm() {
  dishForm = { mode: 'create', editingId: null, allergens: [] };
  document.getElementById('dish-form-mode').textContent = 'Neu';
  const name = document.getElementById('dish-name'); if (name) name.value = '';
  const price = document.getElementById('dish-price'); if (price) price.value = '';
  const cat = document.getElementById('dish-category'); if (cat) cat.value = '';
  const inp = document.getElementById('dish-allergen-input'); if (inp) inp.value = '';
  renderAllergenTags();
}

function renderAllergenTags() {
  const wrap = document.getElementById('dish-allergen-tags');
  if (!wrap) return;
  wrap.innerHTML = '';
  dishForm.allergens.forEach((a, idx) => {
    const t = document.createElement('span');
    t.className = 'badge badge--gray';
    t.style.cssText = 'display:inline-flex;gap:6px;align-items:center;';
    t.innerHTML = `${esc(a)} <button style="border:none;background:none;cursor:pointer;color:inherit;font-weight:700;" onclick="removeAllergen(${idx})">×</button>`;
    wrap.appendChild(t);
  });
}

function addAllergenFromInput() {
  const el = document.getElementById('dish-allergen-input');
  if (!el) return;
  const raw = el.value.trim();
  if (!raw) return;
  const parts = raw.split(',').map(s => s.trim()).filter(Boolean);
  parts.forEach(p => {
    if (!dishForm.allergens.includes(p)) dishForm.allergens.push(p);
  });
  el.value = '';
  renderAllergenTags();
}

function removeAllergen(idx) {
  dishForm.allergens.splice(idx, 1);
  renderAllergenTags();
}

document.addEventListener('keydown', (e) => {
  if (e.target && e.target.id === 'dish-allergen-input') {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      addAllergenFromInput();
    }
  }
});

function editDish(id) {
  const store = DISH_STORE;
  const dish = store?.dishes?.find(d => d.id === id);
  if (!dish) return;
  dishForm.mode = 'edit';
  dishForm.editingId = id;
  dishForm.allergens = Array.isArray(dish.allergens) ? dish.allergens.slice() : [];
  document.getElementById('dish-form-mode').textContent = 'Bearbeiten';
  document.getElementById('dish-name').value = dish.name || '';
  document.getElementById('dish-price').value = dish.price ?? '';
  document.getElementById('dish-category').value = dish.category || '';
  renderAllergenTags();
}

async function saveDish() {
  const name = (document.getElementById('dish-name').value || '').trim();
  const price = parseFloat(document.getElementById('dish-price').value);
  const category = document.getElementById('dish-category').value;
  const allergens = dishForm.allergens.slice();

  // UI-Validierung
  if (!name) return toast('Name ist erforderlich.', 'err');
  if (!price || price <= 0) return toast('Preis muss > 0 sein.', 'err');
  if (!category) return toast('Kategorie ist erforderlich.', 'err');

  const isEdit = dishForm.mode === 'edit' && dishForm.editingId;
  const tempId = 'tmp-' + Date.now();

  if (!DISH_STORE) await loadDishStore();

  if (!isEdit) {
    const optimistic = {
      id: tempId,
      name,
      price: Math.round(price * 100) / 100,
      category,
      allergens,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
    };
    DISH_STORE.dishes.unshift(optimistic);
    resetDishForm();
    hydrateDishCategorySelects();
    renderDishList();
    toast('Speichern…', 'warn');

    try {
      const res = await fetchJson(API_BASE + '/dishes.php', {
        method: 'POST',
        headers: apiHeaders(),
        body: JSON.stringify({ action:'create', name, price, category, allergens }),
      });
      const saved = res.dish;
      const idx = DISH_STORE.dishes.findIndex(d => d.id === tempId);
      if (idx >= 0) DISH_STORE.dishes[idx] = saved;
      hydrateDishCategorySelects();
      renderDishList();
      toast('Gericht gespeichert ✓', 'ok');
    } catch (e) {
      DISH_STORE.dishes = DISH_STORE.dishes.filter(d => d.id !== tempId);
      hydrateDishCategorySelects();
      renderDishList();
      toast('Fehler: ' + e.message, 'err');
    }
    return;
  }

  const id = dishForm.editingId;
  const prev = DISH_STORE.dishes.find(d => d.id === id);
  if (!prev) return toast('Gericht nicht gefunden.', 'err');

  // optimistic update
  Object.assign(prev, { name, price: Math.round(price*100)/100, category, allergens, updatedAt: new Date().toISOString() });
  renderDishList();
  toast('Speichern…', 'warn');

  try {
    const res = await fetchJson(API_BASE + '/dishes.php', {
      method: 'POST',
      headers: apiHeaders(),
      body: JSON.stringify({ action:'update', id, name, price, category, allergens }),
    });
    const saved = res.dish;
    const idx = DISH_STORE.dishes.findIndex(d => d.id === id);
    if (idx >= 0) DISH_STORE.dishes[idx] = saved;
    resetDishForm();
    hydrateDishCategorySelects();
    renderDishList();
    toast('Gericht gespeichert ✓', 'ok');
  } catch (e) {
    toast('Fehler: ' + e.message, 'err');
    refreshDishStore();
  }
}

function deleteDish(id) {
  openConfirm('Gericht löschen?', 'Dieses Gericht wird dauerhaft gelöscht.', async () => {
    if (!DISH_STORE) return;
    const idx = DISH_STORE.dishes.findIndex(d => d.id === id);
    if (idx < 0) return;
    const removed = DISH_STORE.dishes[idx];
    DISH_STORE.dishes.splice(idx, 1);
    hydrateDishCategorySelects();
    renderDishList();
    toast('Löschen…', 'warn');
    try {
      await fetchJson(API_BASE + '/dishes.php', {
        method: 'POST',
        headers: apiHeaders(),
        body: JSON.stringify({ action:'delete', id }),
      });
      toast('Gelöscht ✓', 'ok');
    } catch (e) {
      DISH_STORE.dishes.splice(idx, 0, removed);
      hydrateDishCategorySelects();
      renderDishList();
      toast('Fehler: ' + e.message, 'err');
    }
  });
}

function openAddCategoryModal() {
  document.getElementById('cat-system').value = state.system;
  document.getElementById('cat-label').value = '';
  document.getElementById('cat-key').value = '';
  document.getElementById('add-category-modal').classList.add('open');
  document.getElementById('cat-label').focus();
}

function closeAddCategoryModal() {
  document.getElementById('add-category-modal').classList.remove('open');
}

async function createCategory() {
  const system = document.getElementById('cat-system').value;
  const label  = (document.getElementById('cat-label').value || '').trim();
  const key    = (document.getElementById('cat-key').value || '').trim();
  if (!label) return toast('Kategorie-Name ist erforderlich.', 'err');
  try {
    await fetchJson(API_BASE + '/categories.php', {
      method: 'POST',
      headers: apiHeaders(),
      body: JSON.stringify({ system, label, key }),
    });
    closeAddCategoryModal();
    toast('Kategorie gespeichert ✓', 'ok');
    await loadDishStore(true);
    hydrateDishCategorySelects();
  } catch (e) {
    toast('Fehler: ' + e.message, 'err');
  }
}

// ═══════════════════════════════════════════════════════
// MODALS
// ═══════════════════════════════════════════════════════
let confirmCb = null;
function openConfirm(title, text, cb) {
  document.getElementById('confirm-title').textContent = title;
  document.getElementById('confirm-text').textContent  = text;
  confirmCb = cb;
  document.getElementById('confirm-modal').classList.add('open');
}
function closeConfirm() {
  document.getElementById('confirm-modal').classList.remove('open');
  confirmCb = null;
}
document.getElementById('confirm-ok').addEventListener('click', () => {
  closeConfirm(); if (confirmCb) confirmCb();
});
document.querySelectorAll('.modal-backdrop').forEach(b => {
  b.addEventListener('click', e => { if (e.target===b) b.classList.remove('open'); });
});

// ═══════════════════════════════════════════════════════
// TOAST
// ═══════════════════════════════════════════════════════
function toast(msg, type='') {
  const wrap = document.getElementById('toast-wrap');
  const t    = document.createElement('div');
  t.className = 'toast toast--' + type;
  t.textContent = msg;
  wrap.appendChild(t);
  requestAnimationFrame(() => t.classList.add('show'));
  setTimeout(() => { t.classList.remove('show'); setTimeout(()=>t.remove(),400); }, 3000);
}

// ═══════════════════════════════════════════════════════
// KEYBOARD
// ═══════════════════════════════════════════════════════
document.addEventListener('keydown', e => {
  if ((e.ctrlKey||e.metaKey) && e.key==='s') { e.preventDefault(); saveWeek(); }
  if ((e.ctrlKey||e.metaKey) && e.key==='ArrowLeft')  changeKW(-1);
  if ((e.ctrlKey||e.metaKey) && e.key==='ArrowRight') changeKW(+1);
  if (e.key==='Escape') {
    closeDb(); closeConfirm(); closeKWModal();
    document.getElementById('db-modal').classList.remove('open');
  }
});

// ═══════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════
if (getAdminKey()) {
  document.getElementById('login-screen').style.display = 'none';
  document.getElementById('app').classList.add('visible');
  updateKWLabel();
  loadWeek();
  loadDishStore().catch(() => {});
}
</script>
</body>
</html>
