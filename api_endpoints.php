<?php  

header('application/json');

$file='users.json'; 
// creating a file users.json to save data so our data variable doesnt get re intialized everytime the api endpoint gets called.
if (!file_exists($file)) {
    $data = [
        ["id"=> 1 , "name" =>"hamza" , "email"=>"hamzaarfan@gmail.com", "password" =>"23502350" ],
        ["id"=> 2 , "name" =>"ahmed" , "email"=>"ahmed@gmail.com", "password" =>"23502350" ]
    ];
    file_put_contents($file, json_encode($data));
} else {
    // only first time the file gets populated with the dummy data and the data variable always gets the data from the file afterwards
    $data = json_decode(file_get_contents($file), true);
}
$requestmethord=$_SERVER['REQUEST_METHOD'];

//sending all the avaialable users data
if($requestmethord == 'GET')
{
    echo json_encode($data);
}
//Adding new user 
if($requestmethord == 'POST')
{
    $input_received = json_decode(file_get_contents('php://input'), true);
    // checking is everydata is recived for the new user to be entered
    if(isset($input_received['name']) && isset($input_received['email']) && isset($input_received['password'])){
        $newUser = [
            "id" => count($data) + 1,
            "name" => $input_received['name'],
            "email" => $input_received['email'],
            "password" => $input_received['password']
        ];
        $data[] = $newUser;
        file_put_contents($file, json_encode($data));
        echo json_encode($newUser);
    }
}
// updating new user
if($requestmethord == 'PUT')
{
    $input_received = json_decode(file_get_contents('php://input'), true);
    if(isset($input_received['id'])) {
        foreach ($data as $key => $user) {
            // finds the user to update whose id was send in the ajax put request
            if ($user['id'] == $input_received['id']) {
                if(isset($input_received['name'])) {
                    $data[$key]['name'] = $input_received['name'];
                }
                if(isset($input_received['email'])) {
                    $data[$key]['email'] = $input_received['email'];
                }
                if(isset($input_received['password'])) {
                    $data[$key]['password'] = $input_received['password'];
                }
                file_put_contents($file, json_encode($data));
                echo json_encode($data[$key]);
                break;
            }
        }
    } 
}
//deleting exsisting user
if($requestmethord == 'DELETE')
{
    $input_received = json_decode(file_get_contents('php://input'), true);
    if(isset($input_received['id'])) {
        foreach ($data as $key => $user) {
            // finds the user to delete whichs id was sent from the delete ajax request
            if ($user['id'] == $input_received['id']) {
                unset($data[$key]);
                $data = array_values($data);
                file_put_contents($file, json_encode($data));
                echo json_encode($data);
                break;
            }
        }
    } else {
        echo json_encode(["error" => "User ID not provided"]);
    }
}


?>