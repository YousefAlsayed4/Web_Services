<?php
require_once("vendor/autoload.php");

$item=array();

$con = new mainProgram;
if (($_SERVER["REQUEST_METHOD"] == "GET") && isset($_GET["id"])) {

    if ($con->connect()) {
        $item = $con->get_record_by_id($_GET["id"], "id");
    }
}

if ($item) {
    echo '<table>';
    echo '<tr>';

    echo '<th>' . "id" . '</th>';
    echo '<th>' . "code" . '</th>';
    echo '<th>' . "Type" . '</th>';
    echo '<th>' . "price" . '</th>';
    echo '<th>' . "rating" . '</th>';
    echo '<th>' . "img" . '</th>';
    echo "</tr>";

        echo '<tr>';
        echo '<td>' . $item->id . '</td>';
        echo '<td>' . $item->PRODUCT_code . '</td>';
        echo '<td>' . $item->product_name . '</td>';
        echo '<td>' . $item->list_price. '</td>';
        echo '<td>' . $item->Rating. '</td>';
        echo '<td>' ."<img width='200' height='200' src='". "/php/Lab4&5/images/".$item->Photo."'>". '</td>';
        echo '</tr>';

    echo '</table>';
}

$con->disconnect();