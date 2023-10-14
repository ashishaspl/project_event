<?php
function fileName()
{
    $file = 'entry.txt';
    return $file;
}

function readEntriesInFile()
{
    $file = fileName();
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}

function inputDataDisplay($eventID, $datetimeStr, $time, $ampm, $message)
{
    $dateHR  =date('d.m.Y ',$datetimeStr);

    $output = "Id: $eventID,<br>";
    $output .= "Date: $dateHR,<br>";
    $output .= "Time: $time $ampm,<br>";
    $output .= "Message: $message.<br><br>";

    return $output;
}

function dateValidate($inputDate)
{
    //direct date
//    date_default_timezone_set('UTC');
//    $datefun = date_create_from_format('d-m-Y', $inputDate);

    date_default_timezone_set('UTC');
     $datefun = strtotime($inputDate);

    if ($datefun !== false) {
        return $datefun;
    } else {
        return false;
    }
}

function eventADD($datefun, $time, $ampm, $message, $eventID = "N/A")
{
    try {
        $dateFormate = dateValidate($datefun);
        if ($dateFormate === false) {
            echo "Invalid date format.";
            return;
        }

        $arrData = [
            'id' => $eventID,
            'date' => $dateFormate,
            'time' => $time,
            'ampm' => $ampm,
            'message' => $message,
        ];

        $existing_entries = readEntriesInFile();
        $existing_entries[] = $arrData;
        file_put_contents(fileName(), json_encode($existing_entries, JSON_PRETTY_PRINT));

        echo inputDataDisplay($eventID, $dateFormate, $time, $ampm, $message);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"] ?? '';
    $time = $_POST["time"] ?? '';
    $ampm = $_POST["ampm"] ?? '';
    $message = $_POST["message"] ?? '';
    $eventID = $_REQUEST["id"] ?? "N/A";

    if (empty($date) || empty($time) || empty($ampm) || empty($message)) {
        echo "Pleases fill all fields.";
    } else {
        eventADD($date, $time, $ampm, $message, $eventID);
        header('Location: event.php');
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
<form action="" method="post">


    <label for="date">Date:</label>
    <input type="text" id="date" name="date" class="datepicker" required
           value="<?php echo htmlspecialchars($_POST["date"] ?? '', ENT_QUOTES); ?>">
    <br><br>

    <label for="time">Time:</label>
    <input type="text" id="time" name="time" pattern="^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$" required
           value="<?php echo htmlspecialchars($_POST["time"] ?? '', ENT_QUOTES); ?>">

    <select id="ampm" name="ampm">
        <option value="AM">AM</option>
        <option value="PM">PM</option>
    </select>
    <br><br>
    <label for="text">message:</label>
    <textarea name="message" value="<?php echo htmlspecialchars($_POST["message"] ?? '', ENT_QUOTES); ?>"></textarea>
    <br><br>
    <input type="submit" value="Submit">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy'
        });

    });

</script>
</body>
</html>
