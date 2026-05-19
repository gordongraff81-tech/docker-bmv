"""
Speiseplan-Manager Backend
Flask REST API mit SQLAlchemy ORM
"""
from flask import Flask, request, jsonify
from flask_cors import CORS
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import func
import os
from datetime import datetime
import json

# ════════════════════ INIT ════════════════════
app = Flask(__name__)
CORS(app)

# Database Config
database_url = os.getenv('DATABASE_URL', 'sqlite:////data/speiseplan.db')
app.config['SQLALCHEMY_DATABASE_URI'] = database_url
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)

# ════════════════════ MODELS ════════════════════

class Category(db.Model):
    """Menü-Kategorien (8 Stück)"""
    __tablename__ = 'categories'
    
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), unique=True, nullable=False)
    display_name = db.Column(db.String(100), nullable=False)
    default_price = db.Column(db.Float, nullable=False)
    position = db.Column(db.Integer, nullable=False)
    
    dishes = db.relationship('Dish', backref='category', lazy=True, cascade='all, delete-orphan')
    
    def to_dict(self):
        return {
            'id': self.id,
            'name': self.name,
            'display_name': self.display_name,
            'default_price': self.default_price,
            'position': self.position
        }


class Dish(db.Model):
    """Gerichte (Pool pro Kategorie)"""
    __tablename__ = 'dishes'
    
    id = db.Column(db.Integer, primary_key=True)
    category_id = db.Column(db.Integer, db.ForeignKey('categories.id'), nullable=False)
    name = db.Column(db.String(255), nullable=False)
    price = db.Column(db.Float, nullable=False)
    allergens = db.Column(db.String(255), default='')
    description = db.Column(db.Text, default='')
    active = db.Column(db.Boolean, default=True)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    
    weekly_plans = db.relationship('WeeklyPlanItem', backref='dish', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'category_id': self.category_id,
            'name': self.name,
            'price': self.price,
            'allergens': self.allergens,
            'description': self.description,
            'active': self.active
        }


class WeeklyPlan(db.Model):
    """Wochenplan"""
    __tablename__ = 'weekly_plans'
    
    id = db.Column(db.Integer, primary_key=True)
    year = db.Column(db.Integer, nullable=False)
    week = db.Column(db.Integer, nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    published = db.Column(db.Boolean, default=False)
    
    items = db.relationship('WeeklyPlanItem', backref='plan', lazy=True, cascade='all, delete-orphan')
    
    __table_args__ = (db.UniqueConstraint('year', 'week', name='unique_year_week'),)
    
    def to_dict(self):
        return {
            'id': self.id,
            'year': self.year,
            'week': self.week,
            'published': self.published,
            'created_at': self.created_at.isoformat(),
            'updated_at': self.updated_at.isoformat(),
            'items': [item.to_dict() for item in self.items]
        }


class WeeklyPlanItem(db.Model):
    """Wochenplan Einträge (Tag × Kategorie)"""
    __tablename__ = 'weekly_plan_items'
    
    id = db.Column(db.Integer, primary_key=True)
    plan_id = db.Column(db.Integer, db.ForeignKey('weekly_plans.id'), nullable=False)
    dish_id = db.Column(db.Integer, db.ForeignKey('dishes.id'), nullable=False)
    weekday = db.Column(db.Integer, nullable=False)  # 0=Mo, 4=Fr
    category_id = db.Column(db.Integer, db.ForeignKey('categories.id'), nullable=False)
    
    category = db.relationship('Category', backref='weekly_plan_items', foreign_keys=[category_id])
    
    def to_dict(self):
        return {
            'id': self.id,
            'dish_id': self.dish_id,
            'weekday': self.weekday,
            'category_id': self.category_id,
            'dish': self.dish.to_dict() if self.dish else None
        }


# ════════════════════ API ROUTES ════════════════════

@app.route('/health', methods=['GET'])
def health():
    """Health Check"""
    return jsonify({'status': 'OK', 'timestamp': datetime.utcnow().isoformat()})


# ════════════════════ CATEGORIES ════════════════════

@app.route('/api/categories', methods=['GET'])
def get_categories():
    """Alle Kategorien"""
    categories = Category.query.order_by(Category.position).all()
    return jsonify([cat.to_dict() for cat in categories])


@app.route('/api/categories/<int:cat_id>', methods=['GET'])
def get_category(cat_id):
    """Einzelne Kategorie mit Gerichten"""
    category = Category.query.get_or_404(cat_id)
    result = category.to_dict()
    result['dishes'] = [dish.to_dict() for dish in category.dishes if dish.active]
    return jsonify(result)


# ════════════════════ DISHES ════════════════════

@app.route('/api/dishes', methods=['GET'])
def get_dishes():
    """Alle Gerichte"""
    category_id = request.args.get('category_id', type=int)
    
    query = Dish.query.filter_by(active=True)
    if category_id:
        query = query.filter_by(category_id=category_id)
    
    dishes = query.all()
    return jsonify([dish.to_dict() for dish in dishes])


@app.route('/api/dishes', methods=['POST'])
def create_dish():
    """Neues Gericht erstellen"""
    data = request.get_json()
    
    try:
        dish = Dish(
            category_id=data['category_id'],
            name=data['name'],
            price=data.get('price'),
            allergens=data.get('allergens', ''),
            description=data.get('description', '')
        )
        db.session.add(dish)
        db.session.commit()
        return jsonify(dish.to_dict()), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': str(e)}), 400


@app.route('/api/dishes/<int:dish_id>', methods=['PUT'])
def update_dish(dish_id):
    """Gericht aktualisieren"""
    dish = Dish.query.get_or_404(dish_id)
    data = request.get_json()
    
    try:
        dish.name = data.get('name', dish.name)
        dish.price = data.get('price', dish.price)
        dish.allergens = data.get('allergens', dish.allergens)
        dish.description = data.get('description', dish.description)
        db.session.commit()
        return jsonify(dish.to_dict())
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': str(e)}), 400


@app.route('/api/dishes/<int:dish_id>', methods=['DELETE'])
def delete_dish(dish_id):
    """Gericht deaktivieren"""
    dish = Dish.query.get_or_404(dish_id)
    dish.active = False
    db.session.commit()
    return '', 204


# ════════════════════ WEEKLY PLANS ════════════════════

@app.route('/api/weekly-plans/<int:year>/<int:week>', methods=['GET'])
def get_weekly_plan(year, week):
    """Wochenplan laden"""
    plan = WeeklyPlan.query.filter_by(year=year, week=week).first()
    
    if not plan:
        # Neuer Plan erstellen
        plan = WeeklyPlan(year=year, week=week)
        db.session.add(plan)
        db.session.commit()
    
    return jsonify(plan.to_dict())


@app.route('/api/weekly-plans/<int:year>/<int:week>/items', methods=['POST'])
def update_weekly_plan(year, week):
    """Wochenplan aktualisieren"""
    data = request.get_json()
    
    try:
        plan = WeeklyPlan.query.filter_by(year=year, week=week).first()
        if not plan:
            plan = WeeklyPlan(year=year, week=week)
            db.session.add(plan)
        
        # Alte Items löschen
        WeeklyPlanItem.query.filter_by(plan_id=plan.id).delete()
        
        # Neue Items hinzufügen
        for item_data in data.get('items', []):
            item = WeeklyPlanItem(
                plan_id=plan.id,
                dish_id=item_data['dish_id'],
                weekday=item_data['weekday'],
                category_id=item_data['category_id']
            )
            db.session.add(item)
        
        plan.updated_at = datetime.utcnow()
        db.session.commit()
        return jsonify(plan.to_dict())
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': str(e)}), 400


@app.route('/api/weekly-plans/<int:year>/<int:week>/publish', methods=['POST'])
def publish_weekly_plan(year, week):
    """Wochenplan veröffentlichen"""
    plan = WeeklyPlan.query.filter_by(year=year, week=week).first_or_404()
    plan.published = True
    db.session.commit()
    return jsonify(plan.to_dict())


# ════════════════════ INIT DB ════════════════════

def init_db():
    """Datenbank initialisieren"""
    with app.app_context():
        db.create_all()
        
        # Prüfe ob Kategorien existieren
        if Category.query.count() == 0:
            categories_data = [
                ('vollkost_m1', 'Vollkost M1', 6.40, 1),
                ('leichte_kost_m2', 'Leichte Kost M2', 6.60, 2),
                ('premium_m3', 'Premium M3', 7.40, 3),
                ('tagesmenü_m4', 'Tagesmenü M4', 6.40, 4),
                ('dessert', 'Dessert', 1.80, 5),
                ('rohkost', 'Rohkost', 1.80, 6),
                ('abendessen', 'Abendessen', 5.60, 7),
                ('salat', 'Salat', 5.60, 8),
            ]
            
            for name, display_name, default_price, position in categories_data:
                category = Category(
                    name=name,
                    display_name=display_name,
                    default_price=default_price,
                    position=position
                )
                db.session.add(category)
            
            db.session.commit()
            print("✓ Kategorien erstellt")


if __name__ == '__main__':
    init_db()
    app.run(host='0.0.0.0', port=5000, debug=False)
