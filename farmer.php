<?php

// Farmer's data
$FARMER_PRODUCES = array(
    'vegetables' => array('carrot', 'potato', 'tomato', 'onion', 'garlic'),
    'fruits' => array('apple', 'banana', 'orange', 'grapes', 'strawberry'),
    'grains' => array('wheat', 'rice', 'barley', 'corn', 'quinoa'),
    'legumes' => array('beans', 'lentils', 'peas', 'chickpeas', 'soybeans'),
    'herbs' => array('basil', 'parsley', 'cilantro', 'rosemary', 'thyme'),
    'spices' => array('cinnamon', 'turmeric', 'ginger', 'cumin', 'paprika')
);

// Update quantities for each crop
foreach ($FARMER_PRODUCES as $category => &$crops) {
    // Assign random quantity for each crop
    foreach ($crops as &$crop) {
        $crop = array(
            'name' => $crop,
            'quantity' => rand(10, 100) // Random quantity between 10 and 100
        );
    }
}

// Endpoint to get all produce
function getAllProduce() {
    global $FARMER_PRODUCES;
    return json_encode($FARMER_PRODUCES);
}

// Endpoint to get specific produce
function getProduce($type) {
    global $FARMER_PRODUCES;
    if (isset($FARMER_PRODUCES[$type])) {
        return json_encode($FARMER_PRODUCES[$type]);
    } else {
        return json_encode(array('error' => 'Produce not found'));
    }
}

// Handle API requests
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'getAllProduce':
            echo getAllProduce();
            break;
        case 'getProduce':
            if (isset($_GET['type'])) {
                echo getProduce($_GET['type']);
            } else {
                echo json_encode(array('error' => 'Type parameter missing'));
            }
            break;
        default:
            echo json_encode(array('error' => 'Invalid action'));
    }
} 
else 
{
    echo json_encode(array('error' => 'Action parameter missing'));
}
