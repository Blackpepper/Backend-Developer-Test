<?php

$postData = [
    'trade' => [
        'buyFrom' => [
            'martianid' => 17,
            'items' => [
                [
                    'itemid' => 2,
                    'quantity' => 2,
                ],
                [
                    'itemid' => 5,
                    'quantity' => 1,
                ],
            ],
        ],
        'sellTo' => [
            'martianid' => 15,
            'items' => [
                [
                    'itemid' => 3,
                    'quantity' => 3,
                ],
            ],
        ],
    ],
];

$dataArr = serialize($postData);

$response = $this->post('api/martians-trade',[
    'data' => $dataArr,
]);

?>