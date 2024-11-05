<?php
// Load and decode JSON data
$jsonFile = 'varad.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Handle form submission to add a new entry
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $newVara = [
        "details" => [
            "varanumber" => $_POST["varanumber"],
            "nimetus" => $_POST["nimetus"],
            "seisund" => $_POST["seisund"],
            "maksmus" => $_POST["maksmus"],
            "vastutaja" => $_POST["vastutaja"],
            "lisaaeg" => $_POST["lisaaeg"]
        ]
    ];

    // Add the new item to the array
    $data["vara"][] = $newVara;

    // Update JSON file and check if saved successfully
    if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $message = "New item added successfully!";
    } else {
        $message = "Error adding new item!";
    }
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Varade Haldussüsteem</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .message { color: green; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Varad List</h2>
<?php
if (isset($message)) {
    echo "<div class='message'>{$message}</div>";
}
?>

<button onclick="showAll()">Show All</button>
<button onclick="showNew()">Show Uus</button>
<button onclick="showUsed()">Show Kasutatud</button>
<button onclick="showOldest()">Show 5 Oldest</button>

<table id="varadTable">
    <thead>
    <tr>
        <th>Varanumber</th>
        <th>Nimetus</th>
        <th>Seisund</th>
        <th>Maksmus</th>
        <th>Vastutaja</th>
        <th>Lisa Aeg</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // Render table rows from PHP
    foreach ($data["vara"] as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item["details"]["varanumber"]) . "</td>";
        echo "<td>" . htmlspecialchars($item["details"]["nimetus"]) . "</td>";
        echo "<td>" . htmlspecialchars($item["details"]["seisund"]) . "</td>";
        echo "<td>" . htmlspecialchars($item["details"]["maksmus"]) . "</td>";
        echo "<td>" . htmlspecialchars($item["details"]["vastutaja"]) . "</td>";
        echo "<td>" . htmlspecialchars($item["details"]["lisaaeg"]) . "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>

<h3>Lisa Uus Vara</h3>
<form action="" method="post">
    <label for="varanumber">Varanumber:</label>
    <input type="text" id="varanumber" name="varanumber" required><br><br>

    <label for="nimetus">Nimetus:</label>
    <input type="text" id="nimetus" name="nimetus" required><br><br>

    <label for="seisund">Seisund:</label>
    <select id="seisund" name="seisund">
        <option value="Uus">Uus</option>
        <option value="Kasutatud">Kasutatud</option>
    </select><br><br>

    <label for="maksmus">Maksmus:</label>
    <input type="number" id="maksmus" name="maksmus" required><br><br>

    <label for="vastutaja">Vastutaja:</label>
    <input type="text" id="vastutaja" name="vastutaja" required><br><br>

    <label for="lisaaeg">Lisa Aeg (dd.mm.yyyy):</label>
    <input type="text" id="lisaaeg" name="lisaaeg" required><br><br>

    <input type="submit" name="submit" value="Lisa Vara">
</form>

<script>
    let varadData = <?php echo json_encode($data["vara"]); ?>;

    // Display data in table
    function displayData(data) {
        const tbody = document.querySelector('#varadTable tbody');
        tbody.innerHTML = '';
        data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.details.varanumber}</td>
                    <td>${item.details.nimetus}</td>
                    <td>${item.details.seisund}</td>
                    <td>${item.details.maksmus}</td>
                    <td>${item.details.vastutaja}</td>
                    <td>${item.details.lisaaeg}</td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Show all data
    function showAll() {
        displayData(varadData);
    }

    // Filter by "seisund = Uus"
    function showNew() {
        const filteredData = varadData.filter(item => item.details.seisund === 'Uus');
        displayData(filteredData);
    }

    // Filter by "seisund != Uus"
    function showUsed() {
        const filteredData = varadData.filter(item => item.details.seisund !== 'Uus');
        displayData(filteredData);
    }

    // Show 5 oldest entries based on "lisaaeg"
    function showOldest() {
        const sortedData = [...varadData].sort((a, b) => {
            const dateA = new Date(a.details.lisaaeg.split('.').reverse().join('-'));
            const dateB = new Date(b.details.lisaaeg.split('.').reverse().join('-'));
            return dateA - dateB;
        });
        displayData(sortedData.slice(0, 5));
    }

    // Load data on page load
    window.onload = showAll;
</script>

</body>
</html>
