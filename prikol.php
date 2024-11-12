<?php
// Load and decode JSON data
$jsonFile = 'varad.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

$message = ""; // To store success or error message

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

    // Redirect to avoid form re-submission on refresh
    header("Location: /prikol.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Varade Haldussüsteem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Varad List</h2>
<?php
if (!empty($message)) {
    echo "<div class='message'>{$message}</div>";
}
?>

<button onclick="showAll()">Show All</button>
<button onclick="showNew()">Show Uus</button>
<button onclick="showUsed()">Show Kasutatud</button>
<button onclick="showOldest()">Show 5 Oldest</button>
<button onclick="showJSON()">Show JSON Code</button>
<button onclick="showXML()">Show XML Code (Varahaldussustem.xml)</button>

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

    <label for="lisaaeg">Lisa Aeg:</label>
    <input type="date" id="lisaaeg" name="lisaaeg" required><br><br>

    <input type="submit" name="submit" value="Lisa Vara">
</form>

<!-- Placeholders to display JSON and XML data -->
<div id="jsonDisplay" style="display:none; margin-top: 20px; background-color: #f8f8f8; padding: 10px; border: 1px solid #ddd;">
    <h3>JSON Code:</h3>
    <pre id="jsonContent"></pre>
</div>

<div id="xmlDisplay" style="display:none; margin-top: 20px; background-color: #f8f8f8; padding: 10px; border: 1px solid #ddd;">
    <h3>XML Code (Varahaldussustem.xml):</h3>
    <pre id="xmlContent"></pre>
</div>

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

    // JSON and XML data variables
    const jsonData = <?php echo json_encode($data["vara"], JSON_PRETTY_PRINT); ?>;
    const xmlData = `
<varad>
    <vara varanumber="657483OD">
        <details>
            <varanumber>657483OD</varanumber>
            <nimetus>arvuti</nimetus>
            <seisund>Uus</seisund>
            <maksmus>123</maksmus>
            <vastutaja>Irina Merkulova</vastutaja>
            <lisaaeg>12.12.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657484OD">
        <details>
            <varanumber>657484OD</varanumber>
            <nimetus>telefon</nimetus>
            <seisund>Kasutatud</seisund>
            <maksmus>230</maksmus>
            <vastutaja>Andrei Ivanov</vastutaja>
            <lisaaeg>11.11.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657485OD">
        <details>
            <varanumber>657485OD</varanumber>
            <nimetus>monitor</nimetus>
            <seisund>Uus</seisund>
            <maksmus>150</maksmus>
            <vastutaja>Anna Petrova</vastutaja>
            <lisaaeg>10.10.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657486OD">
        <details>
            <varanumber>657486OD</varanumber>
            <nimetus>printer</nimetus>
            <seisund>Uus</seisund>
            <maksmus>200</maksmus>
            <vastutaja>Maria Smirnova</vastutaja>
            <lisaaeg>09.09.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657487OD">
        <details>
            <varanumber>657487OD</varanumber>
            <nimetus>hiir</nimetus>
            <seisund>Kasutatud</seisund>
            <maksmus>20</maksmus>
            <vastutaja>Pavel Komarov</vastutaja>
            <lisaaeg>08.08.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657488OD">
        <details>
            <varanumber>657488OD</varanumber>
            <nimetus>klaviatuur</nimetus>
            <seisund>Uus</seisund>
            <maksmus>50</maksmus>
            <vastutaja>Sergei Lebedev</vastutaja>
            <lisaaeg>07.07.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657489OD">
        <details>
            <varanumber>657489OD</varanumber>
            <nimetus>tahvelarvuti</nimetus>
            <seisund>Kasutatud</seisund>
            <maksmus>300</maksmus>
            <vastutaja>Oksana Belova</vastutaja>
            <lisaaeg>06.06.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657490OD">
        <details>
            <varanumber>657490OD</varanumber>
            <nimetus>projektor</nimetus>
            <seisund>Uus</seisund>
            <maksmus>500</maksmus>
            <vastutaja>Viktor Sergeev</vastutaja>
            <lisaaeg>05.05.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657491OD">
        <details>
            <varanumber>657491OD</varanumber>
            <nimetus>ruuter</nimetus>
            <seisund>Kasutatud</seisund>
            <maksmus>100</maksmus>
            <vastutaja>Dmitri Kuznetsov</vastutaja>
            <lisaaeg>04.04.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657492OD">
        <details>
            <varanumber>657492OD</varanumber>
            <nimetus>kõvaketas</nimetus>
            <seisund>Uus</seisund>
            <maksmus>80</maksmus>
            <vastutaja>Natalia Pavlova</vastutaja>
            <lisaaeg>03.03.2023</lisaaeg>
        </details>
    </vara>
    <vara varanumber="657493OD">
        <details>
            <varanumber>657493OD</varanumber>
            <nimetus>kõlarid</nimetus>
            <seisund>Kasutatud</seisund>
            <maksmus>60</maksmus>
            <vastutaja>Vladimir Fedorov</vastutaja>
            <lisaaeg>02.02.2023</lisaaeg>
        </details>
    </vara>
</varad>
    `;

    // Show JSON data
    function showJSON() {
        document.getElementById('jsonContent').textContent = JSON.stringify(jsonData, null, 4);
        document.getElementById('jsonDisplay').style.display = 'block';
        document.getElementById('xmlDisplay').style.display = 'none';
    }

    // Show XML data
    function showXML() {
        document.getElementById('xmlContent').textContent = xmlData.trim();
        document.getElementById('xmlDisplay').style.display = 'block';
        document.getElementById('jsonDisplay').style.display = 'none';
    }

    // Load data on page load
    window.onload = showAll;
</script>

</body>
</html>
