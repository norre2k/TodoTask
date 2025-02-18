<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoTask</title>

<style>

body{
    background-color: bisque;
    text-align: center;
}
h1{ text-decoration: underline;
color: green;}

ul.Ulbox{
text-align: center;
display: inline-block;
color: green;}

ul.ULbox a {
    color: greenyellow;
}

ul.Ulbox a:hover{   color: darkgreen;}

</style>

</head>
<body>

<h1>Todo-Task</h1>

<!-- submit fältet -->
<form method="POST" action="" >
    <input type="text" name="Uppgift" placeholder="lägg till ny uppgift" required>
    <button type="submit" name="submit">Lägg till task</button>
</form>

<?php

$filename = 'Todos.txt'; //detta är då våran text fil

//submit php, if isset kollar så de submitas, $task kollar användarens $_post
// och trim tar bort extra utrymme i texten
// If !empty kollar så man inte submittar något tomt
//Fileputcontent ser till att datan hamnar i txt filen
if (isset($_POST['Uppgift'])) {
    $Todo = trim($_POST['Uppgift']); 
    if (!empty($Todo)) {
        file_put_contents($filename, $Todo . PHP_EOL, FILE_APPEND); //FILE_APPEND ser till så att vi inte tarbort och skriver till ny text utan lägger till en ny uppgift
                                                                    //Medans PHP_EOL ser till så att den nya stringen hamnar på en ny rad.
       
         header("Location: " . $_SERVER['PHP_SELF']);  // Uppdaterar sidan så att vi inte fastnar på remove urlen när vi submittar igen
        exit(); //gör så att man inte behöver submitta 2 gånger efter första delete
    }
}

// hämtar från våran txt fil
if (file_exists($filename)) {
    $data = file_get_contents($filename);
} else {
    $data = ''; // detta gör så att om vi inte har något text i filen så hämtar den en tom sträng
}

// denna del ser till att våra strings blir till en array
//samt så kollar den om våran array är tom eller inte
if (!empty($data)) {
    $data = explode("\n", trim($data));  // här trimmar den samt gör så arrayen läggs på nya rader
} else {
    $data = [];
}

//Detta är en enkel rolig liten funktion som gör att de Todo uppgifter som läggs in genom forms, blir sorterade efter Alfabetisk ordning.
usort($data, function($a, $b) {
    return strcmp($a, $b);  
});


// här börjar våran "delete" funktion
if (isset($_GET['remove'])) {
    $removeIndex = $_GET['remove']; // Get the task index from URL

    // If isset dubbel kollar så att våran index faktiskt finns
    if (isset($data[$removeIndex])) {
        unset($data[$removeIndex]);  // denna del tarbort valda task
        $data = array_values($data);  // Omindexera arrayen

        // denna del sparar den nya listan in till vår Todos.txt fil
        file_put_contents($filename, implode("\n", $data) . "\n");
    }
}


?>
 <!-- här har Jag lagt till våran delete länk, samt en foreach loop så det visas efter varje task
 eftersom jag använder "$data" istället för "$tasks" I remove delen så har jag gjort om
 denna del till "$data as $index => $value" istället för det som stog i exempel koden
vilket var "$tasks as $index => $tasks"
men det funkar exakt lika, bara olika namn på variablerna -->

<ul class="Ulbox">
<?php foreach ($data as $index => $value): ?>
    <li>
        <?php echo htmlspecialchars($value); ?> <a href="?remove=<?php echo $index; ?>" style="color:#1B4D3E;">radera</a> <!-- Jag la till stylingen direkt I href länken då det kronglade i styles elementet -->
</li>
<?php endforeach; ?>
</ul>



</body>
</html>