<?php
/**
 * AIQueryService.php – PHP 8.1
 * Konvertiert deutsche Gerichtsbezeichnungen in englische Pexels-Suchanfragen.
 *
 * Strategie:
 *   1. Statisches Keyword-Mapping (kostenlos, sofort)
 *   2. Falls kein Treffer → OpenAI GPT-4.1-mini (gecacht)
 *
 * Einbindung: require_once __DIR__ . '/AIQueryService.php';
 * Verwendung: $svc = new AIQueryService(); $query = $svc->getQuery($dishName);
 */

declare(strict_types=1);

class AIQueryService
{
    private const CACHE_TTL  = 30 * 24 * 3600; // 30 Tage
    private const OPENAI_URL = 'https://api.openai.com/v1/chat/completions';
    private const MODEL      = 'gpt-4.1-mini';
    private const MAX_TOKENS = 80;

    private string $cacheDir;
    private ?string $apiKey;

    // Statisches Mapping: deutsch (lowercase, Teilstring) → englische Pexels-Query
    private const KEYWORD_MAP = [
        // ── Suppen & Eintöpfe ──────────────────────────────────────
        'gemüsesuppe'         => 'vegetable soup bowl warm',
        'kartoffelsuppe'      => 'potato soup creamy bowl',
        'tomatensuppe'        => 'tomato soup bowl fresh',
        'linsensuppe'         => 'lentil soup bowl rustic',
        'erbsensuppe'         => 'pea soup bowl',
        'hühnersuppe'         => 'chicken noodle soup bowl',
        'gulaschsuppe'        => 'goulash soup hearty',
        'bohnensuppe'         => 'bean soup bowl',
        'nudelsuppe'          => 'noodle soup bowl',
        'möhreneintopf'       => 'carrot stew bowl',
        'eintopf'             => 'hearty stew pot rustic',
        'suppe'               => 'soup bowl plated hot',

        // ── Schnitzel & Paniertes ──────────────────────────────────
        'wiener schnitzel'    => 'wiener schnitzel golden plate',
        'jägerschnitzel'      => 'schnitzel mushroom sauce plate',
        'rahmschnitzel'       => 'schnitzel cream sauce plate',
        'schnitzel'           => 'breaded schnitzel golden crispy plate',

        // ── Rind ──────────────────────────────────────────────────
        'roulade'             => 'beef roulade braised sauce',
        'gulasch'             => 'beef goulash stew plate',
        'rinderbraten'        => 'beef roast sliced plate',
        'hackbraten'          => 'meatloaf sliced plate',
        'frikadellen'         => 'meatballs pan plate',
        'rindergeschnetzeltes'=> 'beef strips cream sauce',

        // ── Schwein ───────────────────────────────────────────────
        'schweinebraten'      => 'pork roast sliced german plate',
        'kassler'             => 'smoked pork chop plate',
        'leberkäse'           => 'bavarian meatloaf plate',
        'koteletts'           => 'pork chops pan plate',
        'schweinehaxe'        => 'pork knuckle roasted plate',
        'schweine'            => 'pork dish plated',

        // ── Geflügel ──────────────────────────────────────────────
        'hühnergeschnetzeltes'=> 'chicken strips cream sauce plate',
        'hühnerfrikassee'     => 'chicken fricassee cream sauce rice',
        'hähnchenbrüst'       => 'chicken breast grilled plated',
        'putenschnitzel'      => 'turkey schnitzel breaded plate',
        'putengeschnetzeltes' => 'turkey strips cream sauce plate',
        'putenbrust'          => 'turkey breast sliced plate',
        'hähnchen'            => 'roasted chicken plated',
        'pute'                => 'turkey roast plated',

        // ── Wurst ─────────────────────────────────────────────────
        'bratwurst'           => 'bratwurst grilled plate',
        'bockwurst'           => 'sausage boiled plate',
        'würstchen'           => 'sausage plate german',
        'wurst'               => 'sausage plate',

        // ── Innereien ─────────────────────────────────────────────
        'leber'               => 'liver onions pan plate',

        // ── Fisch ─────────────────────────────────────────────────
        'lachsfilet'          => 'salmon fillet plate restaurant',
        'forellenfilet'       => 'trout fillet pan plate',
        'zanderfilet'         => 'pike perch fillet plate',
        'schollenfilet'       => 'plaice fillet pan plate',
        'seelachsfilet'       => 'pollock fish fillet plate',
        'heringfilet'         => 'herring fillet plate',
        'matjes'              => 'herring cream sauce plate',
        'lachs'               => 'salmon fillet plate',
        'forelle'             => 'trout fillet plate',
        'fischfilet'          => 'fish fillet golden plate',
        'fisch'               => 'fish dish plated',

        // ── Pasta & Reis ──────────────────────────────────────────
        'spaghetti bolognese' => 'spaghetti bolognese pasta plate',
        'spaghetti carbonara' => 'spaghetti carbonara pasta plate',
        'spaghetti'           => 'spaghetti pasta plate',
        'lasagne'             => 'lasagna baked cheese plate',
        'tortellini'          => 'tortellini pasta plate',
        'penne'               => 'penne pasta plate',
        'nudeln'              => 'pasta dish plate',
        'spätzle'             => 'spaetzle german noodles plate',
        'nudel'               => 'pasta noodles plate',
        'risotto'             => 'risotto creamy plate',
        'curryreis'           => 'curry rice dish plate',
        'reis'                => 'rice dish plated',

        // ── Kartoffeln ────────────────────────────────────────────
        'kartoffelpuffer'     => 'potato pancake crispy plate',
        'rösti'               => 'rosti potato crispy plate',
        'kartoffelklöße'      => 'potato dumplings plate',
        'kartoffelpüree'      => 'mashed potatoes creamy plate',
        'bratkartoffeln'      => 'fried potatoes pan plate',
        'salzkartoffeln'      => 'boiled potatoes plate',
        'kartoffel'           => 'potato dish plated',

        // ── Vegetarisch / Gemüse ──────────────────────────────────
        'polentaschnitten'    => 'polenta slices pan plate',
        'polenta'             => 'polenta dish plate',
        'kohlrouladen'        => 'stuffed cabbage rolls plate',
        'gefüllte paprika'    => 'stuffed bell pepper plate',
        'gemüsecurry'         => 'vegetable curry plate',
        'gemüsepfanne'        => 'vegetable stir fry pan plate',
        'brokkoli'            => 'broccoli dish plate',
        'blumenkohl'          => 'cauliflower dish plate',
        'spinat'              => 'spinach creamed plate',
        'rotkohl'             => 'red cabbage braised plate',
        'sauerkraut'          => 'sauerkraut german plate',
        'wirsing'             => 'savoy cabbage plate',
        'gemüse'              => 'roasted vegetables plate',

        // ── Eier ──────────────────────────────────────────────────
        'rührei'              => 'scrambled eggs plate',
        'spiegelei'           => 'fried egg plate',
        'omelette'            => 'omelette plate',
        'ei'                  => 'egg dish plated',

        // ── Desserts ──────────────────────────────────────────────
        'mousse au chocolat'  => 'chocolate mousse elegant dessert',
        'schokoladenmousse'   => 'chocolate mousse dark dessert',
        'vanillepudding'      => 'vanilla pudding dessert',
        'grießbrei'           => 'semolina pudding dessert',
        'milchreis'           => 'rice pudding cinnamon dessert',
        'rote grütze'         => 'red berry compote dessert',
        'apfelstrudel'        => 'apple strudel pastry dessert',
        'pfannkuchen'         => 'pancakes stack dessert plate',
        'quarkkeulchen'       => 'quark fritters apple plate',
        'obstsalat'           => 'fruit salad bowl fresh',
        'joghurt'             => 'yogurt fresh fruit bowl',
        'pudding'             => 'pudding dessert cream',
        'kuchen'              => 'cake slice plate dessert',
        'eis'                 => 'ice cream dessert plate',

        // ── Salate & Rohkost ──────────────────────────────────────
        'gurkensalat'         => 'cucumber salad fresh plate',
        'tomatensalat'        => 'tomato salad fresh plate',
        'möhrensalat'         => 'carrot salad grated plate',
        'krautsalat'          => 'coleslaw fresh plate',
        'blattsalat'          => 'green leaf salad plate',
        'fleischsalat'        => 'meat salad plate',
        'rohkost'             => 'raw vegetable salad fresh',
        'salat'               => 'salad bowl fresh plate',

        // ── Abendessen / Brotmahlzeit ─────────────────────────────
        'käsebrot'            => 'cheese bread plate',
        'aufschnitt'          => 'cold cuts deli plate',
        'toast'               => 'toast plate',
        'brot'                => 'bread plate deli',
        'käse'                => 'cheese plate',
        'quark'               => 'quark fresh curd plate',
    ];

    public function __construct()
    {
        $this->apiKey   = getenv('OPENAI_API_KEY') ?: null;
        $this->cacheDir = dirname(__DIR__) . '/data/ai_query_cache/';
        if (!is_dir($this->cacheDir)) {
            @mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Gibt die beste englische Pexels-Suchanfrage für ein deutsches Gericht zurück.
     */
    public function getQuery(string $dishName): string
    {
        $dishName = trim($dishName);
        if ($dishName === '') {
            return 'food dish plated restaurant';
        }

        // 1. Statisches Mapping
        $static = $this->staticLookup($dishName);
        if ($static !== null) {
            return $static;
        }

        // 2. AI-Fallback (nur wenn API-Key vorhanden)
        if ($this->apiKey !== null) {
            return $this->aiLookup($dishName);
        }

        // 3. Letzter Fallback: bereinigter Name + Suffix
        return $this->buildFallback($dishName);
    }

    // ── Private Methoden ────────────────────────────────────────────

    private function staticLookup(string $name): ?string
    {
        $lower = mb_strtolower($name, 'UTF-8');

        // Exakter Treffer
        if (isset(self::KEYWORD_MAP[$lower])) {
            return self::KEYWORD_MAP[$lower];
        }

        // Partieller Treffer – längsten Schlüssel bevorzugen
        $best = null;
        $bestLen = 0;
        foreach (self::KEYWORD_MAP as $key => $val) {
            if (str_contains($lower, $key) && strlen($key) > $bestLen) {
                $best    = $val;
                $bestLen = strlen($key);
            }
        }

        return $best;
    }

    private function aiLookup(string $dishName): string
    {
        $cacheKey  = md5(mb_strtolower($dishName, 'UTF-8'));
        $cacheFile = $this->cacheDir . $cacheKey . '.txt';

        // Cache lesen
        if (file_exists($cacheFile)) {
            $age = time() - filemtime($cacheFile);
            if ($age < self::CACHE_TTL) {
                $cached = trim((string) file_get_contents($cacheFile));
                if ($cached !== '') {
                    return $cached;
                }
            }
        }

        $result = $this->callOpenAI($dishName);

        // Valides Ergebnis cachen
        if ($result !== null && $result !== '') {
            file_put_contents($cacheFile, $result, LOCK_EX);
            return $result;
        }

        return $this->buildFallback($dishName);
    }

    private function callOpenAI(string $dishName): ?string
    {
        $prompt = $this->buildPrompt($dishName);

        $payload = json_encode([
            'model'       => self::MODEL,
            'max_tokens'  => self::MAX_TOKENS,
            'temperature' => 0.3,
            'messages'    => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $ctx = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => implode("\r\n", [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiKey,
                    'User-Agent: BMV-Menuservice/1.0',
                ]),
                'content'       => $payload,
                'timeout'       => 8,
                'ignore_errors' => true,
            ],
        ]);

        $raw = @file_get_contents(self::OPENAI_URL, false, $ctx);
        if ($raw === false) {
            return null;
        }

        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        $text = $data['choices'][0]['message']['content'] ?? null;
        if ($text === null) {
            return null;
        }

        return $this->sanitizeQuery(trim($text));
    }

    private function buildPrompt(string $dishName): string
    {
        return <<<PROMPT
You are a food photography search query generator.
Convert the German dish name into ONE English search query for a stock photo API (Pexels/Unsplash).

RULES:
- Output EXACTLY ONE line, no numbering, no explanation
- 8 to 16 words maximum
- Describe a realistic plated dish (not recipe instructions)
- Include: main ingredient, preparation style, plating context
- Use food photography language

EXAMPLES:
Input: Schweinebraten mit Klößen
Output: pork roast sliced with potato dumplings german plate

Input: Lachs auf Blattspinat
Output: salmon fillet on wilted spinach restaurant style plate

Input: Vanillepudding mit Beeren
Output: vanilla pudding with fresh berries dessert plate

Dish: {$dishName}
Output:
PROMPT;
    }

    private function sanitizeQuery(string $text): string
    {
        // Nur die erste Zeile nehmen, sicherstellen dass kein Unsinn drinbleibt
        $line = strtok($text, "\n");
        if ($line === false) {
            return '';
        }
        // Anführungszeichen und Zeilenumbrüche entfernen
        $line = trim($line, " \t\r\n\"'");
        // Auf maximal 120 Zeichen kürzen
        return mb_substr($line, 0, 120, 'UTF-8');
    }

    private function buildFallback(string $dishName): string
    {
        // Klammerinhalt entfernen: "Schnitzel (veg.)" → "Schnitzel"
        $clean = trim((string) preg_replace('/\s*\(.*?\)\s*/', ' ', $dishName));
        return $clean . ' food dish plated';
    }
}
