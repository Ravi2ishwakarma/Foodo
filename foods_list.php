<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

echo json_encode(
    $foods=[
    [
        "id" => 1,
        "name" => "Paneer Tikka",
        "description" => "Delicious grilled paneer with spices.",
        "price" => 120,
        "image" => "https://www.cookwithmanali.com/wp-content/uploads/2015/07/Restaurant-Style-Recipe-Paneer-Tikka.jpg"
    ],
    [
        "id" => 2,
        "name" => "Veg Thali",
        "description" => "A complete vegetarian meal with roti, rice, dal, sabzi.",
        "price" => 150,
        "image" => "https://content.jdmagicbox.com/v2/comp/mumbai/k9/022pxx22.xx22.220811094333.w9k9/catalogue/the-royal-thali-pure-veg-andheri-east-mumbai-north-indian-delivery-restaurants-y6dsqh4upa.jpg"
    ],
    [
        "id" => 3,
        "name" => "Aloo Paratha",
        "description" => "Stuffed flatbread with spicy mashed potato.",
        "price" => 70,
        "image" => "https://pipingpotcurry.com/wp-content/uploads/2022/11/Aloo-Paratha-Piping-Pot-Curry.jpg"
    ],
    [
        "id" => 4,
        "name" => "Sev Tamatar",
        "description" => "The special dish of Foodo Online that is tastiest sabzi ",
        "price" => 120,
        "image" => "https://nishamadhulika.com/imgpst/featured/Sev-Tamatar-recipe.JPG"
    ]
    
]);
