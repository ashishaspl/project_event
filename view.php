<?php
function readEntriesInFile()
{
    $existing_entries = json_decode(file_get_contents('entry.txt'), true);
    return $existing_entries;
}

function writeEntriesInFile($entries) //delete mate karyu chhe.
{
    file_put_contents('entry.txt', json_encode(array_values($entries), JSON_PRETTY_PRINT));
}

function deleteEntry($id)
{
    $existing_entries = readEntriesInFile();

    $filteredEntries = array_filter($existing_entries, function ($item) use ($id) {
        return $id !== $item['id'];
    });

//    file_put_contents('entry.txt', json_encode(array_values($filteredEntries), JSON_PRETTY_PRINT));

    writeEntriesInFile($filteredEntries); // above function used here file put content mate.
}
function addEntryShow($id)
{
    $existing_entries = readEntriesInFile();

    foreach ($existing_entries as $item) {
        if ($id == $item['id']) {
          $dateHR  =date('d.m.Y ',$item['date']);
            echo "Id=>" . $item['id'] . ",   Date=>" . $dateHR . ", Time=>" . $item['time'] . " " . $item['ampm'] . ",  " . "Message=>" . $item['message'].".";
        }
    }
}

if (isset($_POST['delete'])) {
    $idToDelete = $_REQUEST['id'];
    deleteEntry($idToDelete);
//    echo "Delete successful";
    header('Location: event.php');
}

$idToFind = $_REQUEST['id'];
addEntryShow($idToFind);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post">
    <button type="submit" name="delete" value="delete" id="">Delete</button>
</form>
</body>
</html>
