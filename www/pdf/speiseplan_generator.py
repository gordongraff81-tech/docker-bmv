#!/usr/bin/env python3
"""
BMV Speiseplan PDF-Generator
Aufruf: python3 speiseplan_generator.py --json PATH --year 2025 --kw 12 [--type ear|kantine] --out OUTPUT.pdf
"""
import argparse, json, sys
from datetime import date, timedelta
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.units import mm

# ── CLI ───────────────────────────────────────────────────────
p = argparse.ArgumentParser()
p.add_argument('--json',  required=True)
p.add_argument('--year',  type=int, required=True)
p.add_argument('--kw',    type=int, required=True)
p.add_argument('--type',  default='ear', choices=['ear','kantine'])
p.add_argument('--out',   required=True)
args = p.parse_args()

# ── Daten ─────────────────────────────────────────────────────
with open(args.json, encoding='utf-8') as f:
    SP = json.load(f)

KW      = args.kw
YEAR    = args.year
TYPE    = args.type
PRICES  = SP.get('prices',  {'1':7.50,'2':7.20,'3':9.80,'4':6.50})
APRICES = SP.get('addon_prices', {'D':1.80,'R':1.80,'A':5.50,'S':5.50})

# Montag dieser KW
monday = date(YEAR, 1, 1)
# ISO week: finde den Montag
import datetime
monday = datetime.date.fromisocalendar(YEAR, KW, 1)

DAYS_DATA = {}
for d in SP.get('days', []):
    DAYS_DATA[d['date']] = d

DAY_ABBR = ['MO','DI','MI','DO','FR','SA','SO']

# ── Farben ────────────────────────────────────────────────────
NAVY   = colors.HexColor('#0B2A5B')
NAVY2  = colors.HexColor('#154a8a')
ORANGE = colors.HexColor('#D95A00')
BGROW  = colors.HexColor('#EEF2F7')
BGHEAD = colors.HexColor('#DDE4EE')
BGDIS  = colors.HexColor('#F0F4F8')   # Sa/So Zusatz deaktiviert
GRID   = colors.HexColor('#B8C8DC')
GRAY   = colors.HexColor('#6B7A90')
WHITE  = colors.white
BLACK  = colors.black

PW, PH = A4
ML = 14*mm
MR = 14*mm
TW = PW - ML - MR
PAD  = 2.0
PAD2 = 1.8

# Spalten
C_TAG = 9*mm
C_M   = (TW - C_TAG) / 4

GAP_BS = 4*mm
BS_W   = (TW - GAP_BS) / 2
BS_C0  = 9*mm
BS_C   = (BS_W - BS_C0) / 4

# ── Hilfsfunktionen ───────────────────────────────────────────
def fp(v):
    return f"{float(v):.2f}\u00a0\u20ac".replace('.', ',')

def rfill(c, x, y, w, h, col):
    c.setFillColor(col)
    c.rect(x, y, w, h, fill=1, stroke=0)

def dtxt(c, x, y, txt, sz=7, bold=False, col=BLACK, align='left'):
    fn = 'Helvetica-Bold' if bold else 'Helvetica'
    c.setFont(fn, sz)
    c.setFillColor(col)
    if align == 'center': c.drawCentredString(x, y, txt)
    elif align == 'right': c.drawRightString(x, y, txt)
    else: c.drawString(x, y, txt)

def wtxt(c, x, y, txt, max_w, sz=6.5, bold=False, col=BLACK, lh=None):
    """Wrap + draw. Returns y after last line."""
    fn  = 'Helvetica-Bold' if bold else 'Helvetica'
    lh  = lh or sz * 1.25
    c.setFont(fn, sz); c.setFillColor(col)
    words = txt.split()
    lines, line = [], ''
    for w in words:
        t = (line+' '+w).strip()
        if c.stringWidth(t, fn, sz) <= max_w: line = t
        else:
            if line: lines.append(line)
            line = w
    if line: lines.append(line)
    cy = y
    for l in lines:
        c.drawString(x, cy, l)
        cy -= lh
    return cy

def mwrap(c, txt, max_w, sz=6.5, bold=False):
    """Measure wrapped text height."""
    fn = 'Helvetica-Bold' if bold else 'Helvetica'
    lh = sz * 1.25
    words = txt.split(); n = 0; line = ''
    for w in words:
        t = (line+' '+w).strip()
        if c.stringWidth(t, fn, sz) <= max_w: line = t
        else:
            if line: n += 1
            line = w
    if line: n += 1
    return max(n, 1) * lh

def hline(c, x, y, w, col=GRID, lw=0.3):
    c.setStrokeColor(col); c.setLineWidth(lw)
    c.line(x, y, x+w, y)

def vline(c, x, y1, y2, col=GRID, lw=0.3):
    c.setStrokeColor(col); c.setLineWidth(lw)
    c.line(x, y1, x, y2)

# ── Zeilenhöhen messen ────────────────────────────────────────
def measure_day_row(c, ds, weekend=False):
    dd = DAYS_DATA.get(ds, {}); mb = {m['menu_number']:m for m in dd.get('menus',[])}
    cw = C_M - 2*PAD; max_h = 0
    nums = [2,3] if weekend else [1,2,3,4]
    for n in nums:
        if n not in mb: max_h = max(max_h, 10); continue
        m = mb[n]; t = m.get('title',''); al = m.get('allergens','')
        vg = m.get('vegetarian',False); pr = m.get('price',None)
        std = float(PRICES.get(str(n),0))
        h = PAD2 + mwrap(c, t, cw)
        if al or vg: h += mwrap(c, ('('+al+')' if al else '')+(' vegt.' if vg else ''), cw, sz=5.5)
        if pr is not None and abs(float(pr)-std)>0.01: h += 7.5
        h += PAD2; max_h = max(max_h, h)
    return max(max_h, 14)

def measure_addon_row(c, ds):
    dd = DAYS_DATA.get(ds, {}); ab = {a['code']:a for a in dd.get('addons',[])}
    cw = C_M - 2*PAD; max_h = 0
    for code in ['D','R','A','S']:
        if code not in ab: max_h = max(max_h, 10); continue
        a = ab[code]; t = a.get('name',''); pr = a.get('price',None)
        std = float(APRICES.get(code,0))
        h = PAD2 + mwrap(c, t, cw)
        if pr is not None and abs(float(pr)-std)>0.01: h += 7.5
        h += PAD2; max_h = max(max_h, h)
    return max(max_h, 14)

# ── Zeichenfunktionen ─────────────────────────────────────────
def draw_header(c, y):
    h = 8*mm; yb = y-h
    rfill(c, ML, yb, TW, h, NAVY)
    fri = (monday+timedelta(6)).strftime('%d.%m.%Y')
    mo  = monday.strftime('%d.%m.')
    dtxt(c, ML+3*mm, yb+h/2-2.5,
         f"BMV Menüdienst  Speiseplan  KW {KW:02d}  |  {mo} \u2013 {fri}",
         sz=9, bold=True, col=WHITE)
    dtxt(c, PW-MR-2*mm, yb+h/2-2.5,
         'bestellen.bmv-kantinen.de  \u00b7  Tel.\u00a003327\u00a0/\u00a0574\u202050\u202066',
         sz=6.5, col=colors.HexColor('#AAC4E8'), align='right')
    return yb

def draw_cat_header(c, y, labels, prices, bg=NAVY):
    """labels = [('key','Label'), ...]  prices = {'key': value}"""
    h1=7*mm; h2=5*mm; yb=y-h1-h2
    rfill(c, ML, y-h1,  TW, h1, bg)
    rfill(c, ML, y-h1-h2, TW, h2, BGHEAD)
    for i,(k,lbl) in enumerate(labels):
        cx = ML + C_TAG + i*C_M + C_M/2
        dtxt(c, cx, y-h1+h1/2-3, lbl, sz=7.5, bold=True, col=WHITE, align='center')
        price_v = prices.get(k, prices.get(str(k), 0))
        dtxt(c, cx, y-h1-h2+h2/2-3, fp(price_v), sz=7.5, bold=True, col=ORANGE, align='center')
    # grid
    hline(c, ML, y-h1,    TW, lw=0.4)
    hline(c, ML, y-h1-h2, TW, lw=0.4)
    for i in range(5):
        xv = ML + C_TAG + i*C_M
        vline(c, xv, yb, y, lw=0.4)
    vline(c, ML,    yb, y, lw=0.4)
    vline(c, ML+TW, yb, y, lw=0.4)
    return yb

def draw_day_row(c, y, rh, abbr, ds, nums, bg=WHITE):
    yb = y-rh
    rfill(c, ML, yb, TW, rh, bg)
    dtxt(c, ML+C_TAG/2, yb+rh/2-3, abbr, sz=8.5, bold=True, col=NAVY, align='center')
    dd = DAYS_DATA.get(ds,{}); mb = {m['menu_number']:m for m in dd.get('menus',[])}
    col_map = {n:i for i,n in enumerate([1,2,3,4])}  # Menünummer → Spaltenindex
    for n in [1,2,3,4]:
        ci    = n-1   # 0-based column index always
        x0    = ML + C_TAG + ci*C_M
        cx    = x0 + PAD; cw = C_M-2*PAD
        if n not in nums:
            dtxt(c, x0+C_M/2, yb+rh/2-3, '\u2014', sz=7, col=GRAY, align='center')
            continue
        if n not in mb:
            dtxt(c, x0+C_M/2, yb+rh/2-3, '\u2014', sz=7, col=GRAY, align='center')
            continue
        m   = mb[n]; t = m.get('title',''); al = m.get('allergens','')
        vg  = m.get('vegetarian',False); pr = m.get('price',None)
        std = float(PRICES.get(str(n),0))
        cy  = y - PAD2
        cy  = wtxt(c, cx, cy, t, cw, sz=6.5, col=BLACK) - 0.5
        foot = (('('+al+')') if al else '') + (' vegt.' if vg else '')
        if foot.strip():
            cy = wtxt(c, cx, cy, foot.strip(), cw, sz=5.5, col=GRAY) - 0.5
        if pr is not None and abs(float(pr)-std)>0.01:
            dtxt(c, cx, cy, fp(pr), sz=6.5, bold=True, col=ORANGE)
    # grid
    hline(c, ML, yb, TW)
    vline(c, ML, yb, y); vline(c, ML+TW, yb, y)
    vline(c, ML+C_TAG, yb, y)
    for i in range(1,4): vline(c, ML+C_TAG+i*C_M, yb, y)
    return yb

def draw_addon_row(c, y, rh, abbr, ds, bg=WHITE):
    yb = y-rh
    rfill(c, ML, yb, TW, rh, bg)
    dtxt(c, ML+C_TAG/2, yb+rh/2-3, abbr, sz=8.5, bold=True, col=NAVY, align='center')
    dd = DAYS_DATA.get(ds,{}); ab = {a['code']:a for a in dd.get('addons',[])}
    for ci,code in enumerate(['D','R','A','S']):
        x0 = ML+C_TAG+ci*C_M; cx = x0+PAD; cw = C_M-2*PAD
        if code not in ab:
            dtxt(c, x0+C_M/2, yb+rh/2-3, '\u2014', sz=7, col=GRAY, align='center'); continue
        a = ab[code]; t = a.get('name',''); pr = a.get('price',None)
        std = float(APRICES.get(code,0))
        cy  = y-PAD2
        cy  = wtxt(c, cx, cy, t, cw, sz=6.5, col=BLACK) - 0.5
        if pr is not None and abs(float(pr)-std)>0.01:
            dtxt(c, cx, cy, fp(pr), sz=6.5, bold=True, col=ORANGE)
    hline(c, ML, yb, TW)
    vline(c, ML, yb, y); vline(c, ML+TW, yb, y)
    vline(c, ML+C_TAG, yb, y)
    for i in range(1,4): vline(c, ML+C_TAG+i*C_M, yb, y)
    return yb

def draw_order_section(c, y):
    # Abtrenner
    y -= 3.5*mm
    c.setStrokeColor(GRAY); c.setLineWidth(0.8); c.setDash(4,3)
    c.line(ML, y, ML+TW, y); c.setDash()
    dtxt(c, ML+1*mm, y+1.5, '\u2702  hier abtrennen', sz=6.5, col=GRAY)

    # Bestellschein-Titel
    y -= 2*mm; bh = 7.5*mm; y -= bh
    rfill(c, ML, y, TW, bh, NAVY)
    fri = (monday+timedelta(6)).strftime('%d.%m.%Y'); mo = monday.strftime('%d.%m.')
    dtxt(c, ML+3*mm,    y+bh/2-3, f'BESTELLSCHEIN  \u00b7  KW {KW:02d} \u00b7 {mo} \u2013 {fri}',
         sz=9, bold=True, col=WHITE)
    dtxt(c, ML+TW-2*mm, y+bh/2-3, 'Bestellschluss: Sonntag 24:00 Uhr',
         sz=7, col=colors.HexColor('#AAC4E8'), align='right')

    y -= 2.5*mm
    xL = ML; xR = ML + BS_W + GAP_BS

    # Sektion-Labels
    dtxt(c, xL, y, 'HAUPTMENÜS',        sz=7, bold=True, col=NAVY)
    dtxt(c, xR, y, 'ZUSATZ (Mo\u2013Fr)', sz=7, bold=True, col=NAVY)
    y -= 4.5*mm

    # Header-Zeilen
    hh = 6.5*mm; yh = y-hh
    rfill(c, xL, yh, BS_W, hh, NAVY)
    rfill(c, xR, yh, BS_W, hh, NAVY2)

    for i, lbl in enumerate(['TAG','1','2','3','4']):
        xc = xL+BS_C0/2 if i==0 else xL+BS_C0+(i-1)*BS_C+BS_C/2
        dtxt(c, xc, yh+hh/2-3, lbl, sz=7.5, bold=True, col=WHITE, align='center')
    for i, lbl in enumerate(['TAG','D','R','A','S']):
        xc = xR+BS_C0/2 if i==0 else xR+BS_C0+(i-1)*BS_C+BS_C/2
        dtxt(c, xc, yh+hh/2-3, lbl, sz=7.5, bold=True, col=WHITE, align='center')

    hline(c, xL, yh, BS_W); hline(c, xR, yh, BS_W)
    y = yh

    # Tageszeilen
    rh = 6.5*mm
    for i in range(7):
        bg  = WHITE if i%2==0 else BGROW
        yb  = y - rh
        # Links
        rfill(c, xL, yb, BS_W, rh, bg)
        dtxt(c, xL+BS_C0/2, yb+rh/2-3, DAY_ABBR[i], sz=7.5, bold=True, col=NAVY, align='center')
        for ci in range(1,5):
            n = ci
            xc = xL+BS_C0+(ci-1)*BS_C+BS_C/2
            if i >= 5 and n in [1,4]:
                dtxt(c, xc, yb+rh/2-3, '\u2014', sz=7, col=GRAY, align='center')
        # Rechts
        if i < 5:
            rfill(c, xR, yb, BS_W, rh, bg)
            dtxt(c, xR+BS_C0/2, yb+rh/2-3, DAY_ABBR[i], sz=7.5, bold=True, col=NAVY, align='center')
        else:
            rfill(c, xR, yb, BS_W, rh, BGDIS)

        # Gitter links
        hline(c, xL, yb, BS_W)
        vline(c, xL, yb, y); vline(c, xL+BS_W, yb, y)
        vline(c, xL+BS_C0, yb, y)
        for ci in range(1,4): vline(c, xL+BS_C0+ci*BS_C, yb, y)
        # Gitter rechts
        hline(c, xR, yb, BS_W)
        vline(c, xR, yb, y); vline(c, xR+BS_W, yb, y)
        vline(c, xR+BS_C0, yb, y)
        for ci in range(1,4): vline(c, xR+BS_C0+ci*BS_C, yb, y)

        y = yb

    hline(c, xL, y, BS_W, lw=0.5); hline(c, xR, y, BS_W, lw=0.5)
    y -= 4*mm

    # Kontaktfelder
    flds = [('Tel.:',18*mm),('Adresse:',60*mm),('Name:',55*mm)]
    xf = ML
    for label, fw in flds:
        dtxt(c, xf, y, label, sz=7.5, bold=True, col=NAVY)
        lw2 = c.stringWidth(label,'Helvetica-Bold',7.5)
        x1  = xf+lw2+2*mm; x2 = xf+fw-2*mm
        c.setStrokeColor(NAVY); c.setLineWidth(0.7); c.line(x1, y-1, x2, y-1)
        xf += fw + 2*mm

# ═══════════════════════════════════════════════════════════════
# HAUPT
# ═══════════════════════════════════════════════════════════════
cv = canvas.Canvas(args.out, pagesize=A4)
cv.setTitle(f'BMV Speiseplan KW {KW}/{YEAR}')
cv.setAuthor('BMV Menüdienst')

# Messung
drh = [measure_day_row(cv, (monday+timedelta(i)).strftime('%Y-%m-%d'), i>=5) for i in range(7)]
drh = [max(h,14) for h in drh]
arh = [measure_addon_row(cv, (monday+timedelta(i)).strftime('%Y-%m-%d')) for i in range(5)]
arh = [max(h,14) for h in arh]

y = PH - 8*mm

# Header
y = draw_header(cv, y)
y -= 0.5*mm

# Menü-Kategorien-Header
if TYPE == 'kantine':
    cat_lbls = [('2','2  Leichte Kost'),('3','3  Premium')]
else:
    cat_lbls = [('1','1  Vollkost'),('2','2  Leichte Kost'),('3','3  Premium'),('4','4  Tagesmenü')]
y = draw_cat_header(cv, y, cat_lbls, PRICES)

# Mo–Fr
main_nums = [1,2,3,4] if TYPE == 'ear' else [2,3]
for i in range(5):
    ds = (monday+timedelta(i)).strftime('%Y-%m-%d')
    y  = draw_day_row(cv, y, drh[i], DAY_ABBR[i], ds, main_nums, bg=WHITE if i%2==0 else BGROW)

# Sa / So (immer nur 2 & 3)
for i in [5,6]:
    ds = (monday+timedelta(i)).strftime('%Y-%m-%d')
    y  = draw_day_row(cv, y, drh[i], DAY_ABBR[i], ds, [2,3], bg=WHITE if i%2==0 else BGROW)

hline(cv, ML, y, TW, lw=0.5)
y -= 0.5*mm

# Zusatz (nur EaR)
if TYPE == 'ear':
    addon_lbls = [('D','D  Dessert'),('R','R  Rohkost'),('A','A  Abendessen'),('S','S  Salatteller')]
    y = draw_cat_header(cv, y, addon_lbls, APRICES, bg=NAVY2)
    for i in range(5):
        ds = (monday+timedelta(i)).strftime('%Y-%m-%d')
        y  = draw_addon_row(cv, y, arh[i], DAY_ABBR[i], ds, bg=WHITE if i%2==0 else BGROW)
    hline(cv, ML, y, TW, lw=0.5)
    y -= 0.5*mm

# Legende
y -= 3.5*mm
leg = ('D\u00a0=\u00a0Dessert (1,80\u00a0\u20ac) \u00b7 R\u00a0=\u00a0Rohkost (1,80\u00a0\u20ac) \u00b7 '
       'A\u00a0=\u00a0Abendessen (5,50\u00a0\u20ac) \u00b7 S\u00a0=\u00a0Salatteller (5,50\u00a0\u20ac)  '
       '\u00b7  BMV Menüdienst \u00b7 Am Gutshof 6, 14542 Werder/Havel \u00b7 Tel. 03327\u00a0/\u00a0574\u202050\u202066')
dtxt(cv, ML, y, leg, sz=5.5, col=GRAY)
y -= 2*mm

# Bestellschein
draw_order_section(cv, y)

cv.save()
print(f'OK: {args.out}')
