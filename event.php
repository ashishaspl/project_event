<?php
function readDataInFile()
{
    $existing_entries = file_get_contents('entry.txt','r+');
    $existing_entries_array = json_decode($existing_entries, true);

    $itemIdA = [];

    foreach ($existing_entries_array as $item) {
        $itemIdA[] = $item['id'];
    }
    return $itemIdA;
}
function tableCreate($rows, $cols): string
{
    $eventCount = 1;
    $itemIdA = readDataInFile();

    $table = '<table border="1">';
    for ($row = 1; $row <= $rows; $row++) {
        $table .= '<tr>';
        for ($col = 1; $col <= $cols; $col++) {
            $table .= '<td>';

            $table .= redirectPage($eventCount, $itemIdA);

            $table .= '</td>';
            $eventCount++;
        }
        $table .= '</tr>';
    }
    $table .= '</table>';

    return $table;
}
function redirectPage($eventCount, $itemIdA): string
{
    if (in_array($eventCount, $itemIdA)) {
        return '<a href="view.php?id=' . $eventCount . '">View ' . $eventCount . '</a>';
    } else {
        return '<a href="event_popup.php?id=' . $eventCount . '"> Event ' . $eventCount . '</a>';
    }
}
echo tableCreate(7, 4);
?>
