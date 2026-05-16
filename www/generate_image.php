<?php
function generateDishImage($dishName) {
    // Der "schöne" Prompt: Wir reichern den Namen des Gerichts an
    $prompt = "Professional food photography of $dishName, gourmet plating, "
            . "natural lighting, 8k resolution, highly detailed, appetizing.";

    $data = [
        "prompt" => $prompt,
        "steps" => 25,
        "width" => 1024,
        "height" => 768,
        "cfg_scale" => 7.5
    ];

    $ch = curl_init(getenv('AI_IMAGE_API_URL'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $result = json_decode($response, true);

    // Speichere das Bild lokal im /data Ordner (siehe dein Docker-Volume)
    $imageData = base64_decode($result['images'][0]);
    $filename = "assets/images/dishes/" . md5($dishName) . ".png";
    file_put_contents($filename, $imageData);

    return $filename;
}