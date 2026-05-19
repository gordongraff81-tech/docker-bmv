"""
Import-Skript: Speisenliste in Datenbank laden
Liest aus Speiseplan_Liste.txt und importiert alle Gerichte
"""
import os
import sys
sys.path.insert(0, '/app')

from app import app, db, Category, Dish

# Vollständige Speisenliste nach Kategorien
DISHES_DATA = {
    'vollkost_m1': [
        'Gemüseeintopf mit Wiener',
        'Grüne Bohnensuppe mit Kassler',
        'Erbsensuppe mit Wiener',
        'Eierkuchen mit Zucker und Apfelmus',
        'Grüne Bohnen Eintopf mit Fleischeinlage',
        'Grießbrei mit Früchten',
        'Kohlrüben-eintopf mit Fleischeinlage',
        'Gemüsesuppe vegetarisch',
        'Steckrüben-eintopf mit Fleischeinlage',
        'Linsen Eintopf mit Wiener',
        'Kartoffelsuppe mit Fleischeinlage',
        'Milchreis mit Apfelmus',
        'Gemüseeintopf mit Wursteinlage',
        'Rosenkohl-Eintopf mit Fleischeinlage',
        'Kohlrabisuppe mit Wiener',
        'Weißkohl-Eintopf mit Eisbeinfleisch',
        'Vegetarische Nudelpfanne mit Tomatensoße',
        'Grießbrei mit Erdbeer-Kompott',
        'Kartoffelsuppe mit Bockwurstscheiben',
        'Gemüsesuppe mit Wiener',
        'Kartoffelpuffer mit Zucker und Apfelmus',
        'Möhren-Eintopf mit Fleischeinlage',
        'Grüne Bohnensuppe mit Fleischeinlage',
        'Möhrensuppe mit Fleischeinlage',
        'Brühnudel-Suppe mit Hähnchenfleisch',
        'Graupensuppe mit Fleischeinlage',
        'Kohlrüben-eintopf mit Bauchfleisch',
    ],
    'leichte_kost_m2': [
        'Spargelsuppe mit Fleischklößchen',
        'Wurstgulasch mit Makkaroni',
        'Eierfrikassee mit Reis',
        'Nudel Pfanne mit frischem Gemüse und Tomatensahnesoße',
        'Nudeln mit Stück-iger Tomatensoße und Reibekäse',
        'Kartoffelsalat mit 3 ½ Eiern und Bohnensalat',
        'Gebratene Bockwurst in Ketchup-soße und Reis',
        'Brathering mit Zwiebeln, Kartoffelsalat, Gewürzgurke',
        'Stampfkartoffeln mit 2 Spiegeleier und Leipziger Allerlei',
        'Wurstgulasch mit Spirelli',
        'Blumenkohl und Möhren in heller Soße und Püree',
        'Gemüsemix in holländischer Soße Salzkartoffeln Vegt.',
        'Jägerschnitzel mit Tomatensoße und Nudeln',
        'Vegt. Bratwurst in Paprikasoße und Gemüsereis',
        'Rührei mit Rahmspinat und Kräuterpüree',
        'Pilzragout mit Semmelknödel-scheiben Vegt.',
        'Nudel-Auflauf mit Brokkoli und Tomatensoße',
        'Kaiserschmarrn mit Zucker und Apfelmus',
        'Kartoffelsalat mit 3 ½ Eiern dazu Remouladensoße und Kräuter',
        'Frühlings-Gemüse-Suppe mit Fleischklößchen',
        'Gemüsebällchen auf Butterspaghetti an Tomatensoße',
        '2 gek. Eier in Senfsoße, Möhren und Püree',
        'Spaghetti Bolognese',
        'Gemüseschnitzel auf Reisbett dazu Holländische Sauce',
        'Kesselgulasch (Suppe) mit Spätzle und Brötchen',
        'Gemüsebolognese mit Spaghetti',
        'Spaghetti-Pfanne mit Hackfleisch und Gemüse dazu Tomatensoße',
        'Geschnetzeltes mit Zwiebeln, Reis',
        'Brokkoli mit Hollandaise Kartoffeln',
        'Wurstgulasch mit Nudeln',
        'Kartoffel-Gemüse-Auflauf mit Käsesoße',
        'Käsespätzle mit Röstzwiebeln und Kräutern',
        'Nudeln mit stückiger Tomatensoße, Reibekäse',
        'Nudelsalat mit Boulette und süß-saures Gemüse',
        'Grüne Bohnen Eintopf mit Eisbeinfleisch',
        '2 gek. Eier in Rahmspinat dazu Frühlingspüree',
        'Brokkoli in Hollandaise und Kartoffeln Vegt.',
        'Spirelli mit stückiger Tomatensoße, Reibekäse',
        '2 Spiegeleier auf Stampfkartoffeln dazu Gurkensalat',
        'Gegrillte Gemüsemischung mediterran mit Kräuter-Hollandaise',
        'Bockwurst auf Kartoffel-Salat Salatbeilage',
        'Hähnchengulasch mit Lauch dazu Nudeln',
        'Buntes Eierfrikassee mit Reis',
        'Stampfkartoffeln mit 2 Spiegeleier und Gurkensalat',
        'Nudelauflauf mit Jagdwurstwürfel dazu Tomatencreme',
        'Gemüsebällchen in Paprikasoße and Makkaroni',
    ],
    'premium_m3': [
        'Schweineroulade mit Butterspargel, Soße Kartoffeln',
        'Paprikaschote mit Paprikasoße und Reis',
        'Königsberger Klopse in Kapernsoße, Kartoffeln, Rote Beete',
        'Hühnerfrikassee mit Spargel und Fleischklößchen, Kartoffeln',
        'Fischstäbchen auf Püree dazu Rahmspinat',
        'Boulette auf Wirsing- Rahmgemüse und Kartoffeln',
        'Wildgulasch mit Rotkohl und Kartoffeln',
        'Wildschwein-gulasch mit Preisel-beeren dazu Spätzle',
        '2 kl. Bratwurst auf Bohnenrahmgemüse und Kartoffeln',
        'Schnitzel mit Paprikasoße, Kartoffeln',
        'Kohlroulade mit Bratensoße und Kartoffeln',
        'Alaska Seelachs auf Püree dazu buntes Gemüse, Kräutersoße',
        'Schweinebraten mit Apfel-Rotkohl, Soße und Klöße',
        'Hähnchen Nuggets mit Champignonsoße und Kartoffeln',
        'Gefl. Kohlroulade, Specksoße und Kartoffeln',
        'Schnitzel mit Mischgemüse, Soße, Kartoffeln',
        'Königsberger Klopse in Kapernsoße Karotten, Kartoffeln',
        'Gebr. Lachs auf Bandnudeln dazu Hummersoße mit Erbsen',
        'Falscher Hase mit Butterbohnen, Soße und Kartoffeln',
        'Kaninchenkeule mit Rotkohl, Soße und Kartoffeln',
        'Schaschlikpfanne (Leber/Schweinefleisch) mit buntem Reis',
        'Pikantes Hühnerfrikassee mit Kartoffeln',
        'Boulette auf Porreerahm dazu Kartoffeln',
        'Bratwurst mit Sauerkraut, Soße und Kartoffeln',
        'Paniertes Schollenfilet auf Stampfkartoffeln Zitronenscheibe',
        'Jägerschnitzel mit Tomatensoße und Spirelli',
        'Hähnchenbrust mit Buttererbsen, Soße und Kartoffeln',
        'Putenleber mit Zwiebelsoße Möhrenstifte und Püree',
        'Hähnchenkeule mit Mischgemüse Soße und Kartoffeln',
        'Bauernroulade mit Rotkohl, Soße und Kartoffeln',
        'Schnitzel mit Ei Bratkartoffeln und Gewürzgurke',
        'Rügener Fischpfanne mit Frühlingspüree',
        'Putengulasch mit Gemüsereis',
        'Kaßlerbraten mit Sauerkraut, Soße und Kartoffeln',
        'Hähnchen Nuggets mit Mischgemüserahm und Kartoffeln',
        'Königsberger Klopse in Kapernsoße dazu Kartoffeln, Rote Beete',
        'Schnitzel mit Porreerahm und Kartoffeln',
        'Hacksteak mit Letscho dazu Reis',
        'Fischfilet auf Püree dazu Kräutergemüse',
        'Bratwurst mit Sauerkraut, Soße und Kartoffeln',
        'Schinkenmettwurst auf Grünkohl mit Kartoffeln',
        'Hähnchenschnitzel auf Püree dazu Leipziger Allerlei',
        '2 Bratwürstchen auf Bay. Kraut mit Kartoffeln',
        'Boulette mit Mischgemüse in Soße dazu Kartoffeln',
        'Schnitzel mit Rahmchampignon und Kartoffeln',
        'Fischstäbchen auf Püree dazu buntes Gemüse, Kräutersoße',
        'Spanferkel-rollbraten mit Apfel-Rotkohl and Kartoffeln',
    ],
    'tagesmenü_m4': [
        'Kräuterquark mit Salzkartoffeln und Leinöl',
        'Hähnchenschnitzel auf Nudelsalat, Salatbeilage',
        'Kartoffelsalat mit 2 Wiener und Salatbeilage',
        'Currywurstscheiben in Ketchup-Soße und Reis',
        '3 Kartoffelpuffer mit Zucker und Apfelmus',
        'Kräuterquark mit Salzkartoffeln Leinöl',
        'Bauernsülze mit Bratkartoffeln und Remouladensoße',
        'Pußtabällchen in Paprikasoße und Reis',
        '3 Hefeklöße mit Früchten',
        'Kräuterquark mit frischen Kräutern Kartoffeln, Butter',
        'Kartoffelsalat mit Schinkenröllchen und Rohkost',
        'Nudelsalat mit Boulette und Salatbeilage',
        '3 Eierkuchen mit Zucker und Apfelmus',
        'Kräuterquark mit Kartoffeln und Butter',
        'Boulette auf Kartoffelsalat und Salatbeilage',
        'Sülze mit Bratkartoffeln und Remouladensoße',
        'Geschmorter Kohl mit Hackfleisch dazu Kartoffeln',
        'Quarkkeulchen mit Zucker und Apfelmus',
        'Bärlauch Kräuterquark mit Kartoffeln, Leinöl',
        'Kartoffelsalat mit Schinkenröllchen und Bohnensalat',
        'Nudelsalat mit Hähnchen Nuggets und Salatbeilage',
        'Wurstgulasch "Jäger Art" mit Spirelli',
        '3 Eierkuchen mit Zucker und Apfelmus',
        'Kartoffelsalat mit Backfisch und Rohkost',
        'Backcamembert auf Gebutterte Spaghetti dazu Kräutersoße',
        'Eierkuchen mit Zucker und Apfelmus',
        'Kartoffelsalat mit Schnitzel und Salatbeilage',
        'Kartoffelpuffer mit Apfelmus und Zucker',
    ],
    'dessert': [
        '1 Banane',
        'Götterspeise',
        'Schokopudding mit Sahne',
        '1 Stück Marzipantorte',
        'Rote Grütze mit Sahne',
        'Obstkompott',
        'Schokopudding mit V-Soße',
        '1 Stk Blechkuchen',
        'Fruchtjoghurt 3,5%',
        'Cremedessert',
        'Wackelpudding',
        'Apfelmus',
        'Quarkspeise',
        'Banane in Schokosoße',
        'Rote Grütze mit V-Soße',
        '1 Stück Blechkuchen',
        '1 Pfannkuchen',
        'Frischer Obstsalat',
        'Götterspeise mit V-Soße',
        '1 Stück Bienenstichtorte',
        'Zitronenquark',
        'Götterspeise mit Sahne',
        '1 Stk Kokos-Schoko-Kuchen',
        '1 Stk Schoko Muffins',
    ],
    'rohkost': [
        'Gurkensalat',
        'Tomatensalat',
        'Rettich-Radischen Salat',
        'Bohnensalat',
        'Gurkensalat mit Dill-Joghurt',
        'Möhrenrohkost',
        'Weißkraut-Salat',
        'Chinakohl mit Pfirsich',
        'Tomatensalat mit frischer Minze',
        'Gurken-Tomaten Salat',
        'Kartoffelsalat 200g',
        'Gurkensalat in Sahne',
    ],
    'abendessen': [
        'Bockwurst auf Kartoffelsalat und Salatbeilage',
        '2 kl. Bouletten auf Nudelsalat und Salatbeilage',
        'Matjes Hering Remoulade Butter und Vollkornbrot',
        'Käseplatte mit Laugenstange, Butter Salatbeilage',
        'Bockwurst auf Kartoffelsalat + Salatbeilage',
        '2 Bauernstullen mit Salami und Harzer Käse dazu Gurke und Tomate',
        '2 Bouletten auf Kartoffelsalat und Salatbeilage',
        '2 Mischbrotschnitten mit Zwiebelmett, Leberwurst Garnitur',
        '2 Scheiben Brot mit Schinken und Käse dazu Salat',
        'Eiersalat, mit Brot und Butter',
        'Gebr. Jagdwurst Salatbeilage, Senf und hausgemachter Kartoffelsalat',
        'Hausmacher Blut/Leberwurst ½ Ei mit Brot, Butter',
        '2 Wiener mit Butter und Brot, Senf und Salat',
        'Eiersalat mit Salatbeilage und Toastbrot',
        '2 Bauernschnitten mit Käse und Leberwurst',
        'Brathering mit Mischbrot Zwiebeln Gewürzgurke und Butter',
        'Käseplatte, 3 verschiedene Sorten mit Butter, Brot',
        '2 Scheiben Brot mit Zwiebelmett und Fleischsalat',
        'Schnitzel auf Kartoffelsalat und Salatbeilage',
        'Käseplatte mit Bauernbrot, Butter Salatbeilage',
        '2 Scheiben Brot mit Hausgemachte Leberwurst und Käse dazu Salat',
        'Rührei-Bauernstulle mit Zwiebellauch und Tomatensalat',
        '4 Canapés mit Käse, Mett, Eiersalat, Salami dazu Saure Gurke',
    ],
    'salat': [
        'Gem. Salat mit Feta Tomate, Gurke, Ei Dressing und Brötchen',
        'Gem. Salat mit Tomaten, Mozzarella Balsamico Dressing Brötchen',
        'Bunter Salat mit Ananas, Gemüsebällchen, Dipp dazu Baguettescheiben',
        'Gem. Salat mit Thunfisch, Zwiebeln Baguettescheiben, Dressing',
        'Bunter Sommer-Salat mit Gurke, Tomate, Paprika, Pfirsich, Lauch',
        'Gemischter Salat mit Thunfisch, Ei, Dressing und Zwiebeln Brötchen',
        'Eisbergsalat mit Gurke, Tomate Fetakäse, Ei, Dressing und Brötchen',
        'Gem. Salat mit Hähnchenstreifen, Dipp und Brötchen',
        'Gem. Salatplatte mit Feta, Ei Dressing, Brötchen',
        'Gemischter Salat mit Hähnchenbrust Dressing und Brötchen',
        'Griech. Bauernsalat mit Feta, Paprika, Gurken, Oliven und Dressing, Brötchen',
        'Thunfisch Salat mit Gurke, Tomate und Eisberg, dazu Dressing, Brötchen',
        'Griechischer Bauernsalat Feta, Tomate Paprika, Gurke, Dressing, Brötchen',
        'Gemischter Salat mit Thunfisch, Zwiebeln Ei Dressing und Brötchen',
        'Hähnchenschnitzel auf Nudelsalat and Salatbeilage',
        'Salat mit Mozzarella, Fetawürfel, Weintrauben, Mandarinen und Dressing',
        'Gem. Salat mit Käse und Schinken, Dressing und Brötchen',
        'Eisbergsalat mit Feta, Ei, Gurke, Tomate Dressing Brötchen',
        'Nudelsalat mit Hähnchenbrust und Rohkostsalat',
        'Bunter Salat mit Gemüsebällchen, Ananas dazu Dipp Baguettescheiben',
        'Kartoffelsalat mit Schnitzel, und Gurkensalat',
        'Schweizer Wurstsalat dazu Brötchen und Butter',
    ],
}


def import_dishes():
    """Gerichte in Datenbank importieren"""
    with app.app_context():
        db.create_all()
        
        # Kategorien erstellen wenn nicht existiert
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
            print("✓ 8 Kategorien erstellt")
        
        for category_name, dishes in DISHES_DATA.items():
            category = Category.query.filter_by(name=category_name).first()
            
            if not category:
                print(f"❌ Kategorie {category_name} nicht gefunden")
                continue
            
            # Prüfe ob Gerichte bereits existieren
            if category.dishes:
                print(f"⏭️  Kategorie {category.display_name} hat bereits {len(category.dishes)} Gerichte")
                continue
            
            for dish_name in dishes:
                if not dish_name.strip():
                    continue
                    
                dish = Dish(
                    category_id=category.id,
                    name=dish_name.strip(),
                    price=category.default_price,
                    allergens='',
                    active=True
                )
                db.session.add(dish)
            
            db.session.commit()
            print(f"✓ {len(dishes)} Gerichte zu {category.display_name} hinzugefügt")
        
        # Statistik
        total_dishes = Dish.query.count()
        print(f"\n✓ Import abgeschlossen! Insgesamt {total_dishes} Gerichte in der Datenbank")


if __name__ == '__main__':
    import_dishes()
