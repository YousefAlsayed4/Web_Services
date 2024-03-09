<?php

require_once 'vendor/autoload.php';

$urlParts = explode("/", $_SERVER["REQUEST_URI"]);

echo "<pre>";
print_r($urlParts);
echo "</pre>";

$resource = $urlParts[3];
$resource_id = (isset($urlParts[4]) && is_numeric($urlParts[4])) ? (int) $urlParts[4] : 0;


/**
 * 1-Define METHOD
 * 2-Define RESOURCE
 * 3-Define RESOURCE_ID
 */
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Call a function to handle the GET request
        $data = handleGet($resource, $resource_id)['data'];
        break;
    case 'POST':
        $data = handlePost($resource);
        break;
    case 'PUT':
        echo "Will update";
        break;
    case 'DELETE':
        echo 'Will Delete';
        break;
    default:
        echo 'Not supported';
        break;
}

header('Content-Type: application/json');

if (!empty($data)) {
    echo json_encode($data);
}


function handleGet($resource, $resource_id)
{
    $conn = new mainProgram;

    if ($conn->connect()) {
        if ($resource == 'item') {
            if ($resource_id == 0) {
                $items = $conn->get_data(array("id", "product_name"), 0);
                $conn->disconnect(); // Move disconnect here
                return ["msg" => "success", "items" => $items];
            } else {
                $item = $conn->get_record_by_id($resource_id, "id");
                if (!empty($item) && count($item) > 0) {
                    $conn->disconnect(); // Move disconnect here
                    return ["msg" => "success", "item" => $item];
                } else {
                    $conn->disconnect(); // Move disconnect here
                    return ["Error" => "fail:item not exist"];
                }
            }
        } else {
            $conn->disconnect(); // Move disconnect here
            http_response_code(500);
            return ["Error" => "fail to connected"];
        }
    } else {
        $conn->disconnect(); // Move disconnect here
        http_response_code(404);
        return ["Error" => "fail: resource not exist"];
    }
}


function handlePost($resource)
{
    if ($resource == "item") {
        $conn = new mainProgram;
        if ($conn->connect()) {
            try {
                $item = new items();
                $item->id = $_POST["id"];
                $item->PRODUCT_code = $_POST["PRODUCT_code"];
                $item->product_name = $_POST["product_name"];
                $item->list_price = $_POST["list_price"];
                $item->Units_In_Stock = $_POST["Units_In_Stock"];
                $item->save();
                $conn->disconnect(); // Move disconnect here
                return ["msg" => "add"];
            } catch (\Exception $e) {
                $conn->disconnect(); // Move disconnect here
                http_response_code(500);
                return ["Error" => $e->getMessage()];
            }
        } else {
            http_response_code(500);
            return ["Error" => "fail to connected"];
        }
    } else {
        http_response_code(404);
        return ["Error" => "fail : resource not exist"];
    }
}
