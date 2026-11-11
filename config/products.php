<?php
#Juicy tint
return [
    [
        'id' => 1,
        'name' => 'Juicy Lasting Tint',
        'category' => 'Glossy & Dewy',
        'price' => 18500,
        'description' => 'Glossy fruit-inspired lip tint with long-lasting vibrant color.',
        'image' => 'image1/Juicylastingtint/jlt.webp',
        'shades' => [
            ['name' => 'Honey Dew Melon', 'hex' => '#E8A798','image' =>'image1/Juicylastingtint/honeydewmelon.webp'],
            ['name' => 'Bare Marron', 'hex' => '#C87D77', 'image' =>'image1/Juicylastingtint/baremarron.webp'],
            ['name' => 'Black Berry', 'hex' => '#B86F58', 'image' =>'image1/Juicylastingtint/balckberry.webp'],
            ['name' => 'Snow Strawberry', 'hex' => '#7A3E30', 'image' =>'image1/Juicylastingtint/snowstrawberry.jpg'],
            ['name' => 'MyStic Peach', 'hex' => '#D88C71', 'image'=> 'image1/Juicylastingtint/mysticpeach.avif'],
        ],
        'mix_match' => ['Base' => 'Snow Strawberry', 'Point' => 'Black Berry ']
    ],
    
    [
    'id' => 2,
    'name' => 'Glasting Water Tint',
    'category' => 'Glossy & Dewy',
    'price' => 20000,
    'temporary' => false,
    'description' => 'Feels light like water and leaves a clear, glass-like shine over a natural tint.',
    'image' => 'image1/Glastingwatertint/gwt.jpeg',

    'shades' => [
        ['name' => 'Rose Splash', 'hex' => '#C56B73', 'image' => 'image1/Glastingwatertint/02reddrop.webp'],
        ['name' => 'Coral Mist', 'hex' => '#DC806F', 'image'=> 'image1/Glastingwatertint/glastinwatertint3.webp'],
        ['name' => 'Berry Divine', 'hex' => '#984755', 'image'=> 'image1/Glastingwatertint/glastingwatertint.webp'],
    ],

    'mix_match' => [
        'Base' => 'Coral Mist',
        'Point' => 'Berry Divine',
    ],
    ],
    [
    'id' => 3,
    'name' => 'Dewy-Ful Water Tint',
    'category' => 'Glossy & Dewy',
    'price' => 20000,
    'temporary' => false,
    'description' => 'Offers a dewy and moisturizing lip look for comfortable everyday wear.',
    'image' => 'image1/Dewyfullwatertint/images.jpeg',

    'shades' => [
        ['name' => 'Dewy Pink', 'hex' => '#D98591', 'image' => 'image1/Dewyfullwatertint/dewy01.webp'],
        ['name' => 'Peach Mocha', 'hex' => '#E79A82', 'image' => 'image1/Dewyfullwatertint/dewy2.webp'],
        ['name' => 'Mauve Moon', 'hex' => '#A96878', 'image' => 'image1/Dewyfullwatertint/sewy3.webp'],
    ],

    'mix_match' => [
        'Base' => 'Peach Mocha',
        'Point' => 'Mauve Moon',
    ],
    ],

    #velvet&matte
    [
    'id' => 4,
    'name' => 'Zero Velvet Tint',
    'category' => 'Velvet & Matte',
    'price' => 20000,
    'temporary' => false,
    'description' => 'A soft and lightweight velvet tint that helps blur lip lines.',
    'image' => 'image1/Zerovelvettint/zvt.jpeg',

    'shades' => [
        ['name' => 'Burny Nude', 'hex' => '#B46155', 'image'=> 'image1/Zerovelvettint/zerovv1.webp'],
        ['name' => 'Joyful', 'hex' => '#C75A56', 'image'=> 'image1/Zerovelvettint/zerovv2.webp'],
        ['name' => 'Deep Soul', 'hex' => '#7C3D3B', 'image'=> 'image1/Zerovelvettint/zerovv.webp'],
    ],

    'mix_match' => [
        'Base' => 'Burny Nude',
        'Point' => 'Deep Soul',
    ],
],

[
    'id' => 5,
    'name' => 'Blur Fudge Tint',
    'category' => 'Velvet & Matte',
    'price' => 21000,
    'temporary' => false,
    'description' => 'A fudge-like tint with a smooth and comfortable blurred matte finish.',
    'image' => 'image1/Blurfudgetint/bft.png',

    'shades' => [
        ['name' => 'Pomeloco', 'hex' => '#BB7568', 'image'=> 'image1/Blurfudgetint/o1.webp'],
        ['name' => 'Rosiental', 'hex' => '#A95B5F', 'image'=> 'image1/Blurfudgetint/01.jpg'],
        ['name' => 'Mauvy Night', 'hex' => '#70444E', 'image'=> 'image1/Blurfudgetint/o2.webp'],
    ],

    'mix_match' => [
        'Base' => 'Pomeloco',
        'Point' => 'Mauvy Night',
    ],
],

[
    'id' => 6,
    'name' => 'Zero Matte Lipstick',
    'category' => 'Velvet & Matte',
    'price' => 20000,
    'temporary' => false,
    'description' => 'A classic solid lipstick that feels light and comfortable on the lips.',
    'image' => 'image1/Zeromattelipstick/zml.jpg',

    'shades' => [
        ['name' => 'Coco Nude', 'hex' => '#D29B83', 'image'=> 'image1/Zeromattelipstick/01.webp'],
        ['name' => 'Nougat Sand', 'hex' => '#C48A6F', 'image'=> 'image1/Zeromattelipstick/02.webp'],
        ['name' => 'Current Jam', 'hex' => '#8C2D35', 'image'=> 'image1/Zeromattelipstick/013.webp'],
    ],

    'mix_match' => [
        'Base' => 'Coco Nude',
        'Point' => 'Current Jam',
    ],
],
#lipbalm&care
[
    'id' => 7,
    'name' => 'Glasting Melting Balm',
    'category' => 'Balm & Care',
    'price' => 22000,
    'temporary' => false,

    'description' =>
        'A soft, melting balm that adds a jelly-like shine and helps hide lip lines.',

    'image' =>'image1/Glastingmeltingbalm/07.avif',

    'shades' => [
        [
            'name' => 'Coco Nude',
            'hex' => '#A9634D',
            'image'=> 'image1/Glastingmeltingbalm/glastigbalm.webp',
        ],
        [
            'name' => 'Sorbet Balm',
            'hex' => '#F4A286',
            'image'=> 'image1/Glastingmeltingbalm/011.png',
        ],
        [
            'name' => 'Kare Mauve',
            'hex' => '#B57C84',
            'image'=> 'image1/Glastingmeltingbalm/melting2.webp',
        ],
    ],

    'mix_match' => [
        'Base' => 'Sorbet Balm',
        'Point' => 'Kare Mauve',
    ],
],
];
