<?php

// Load the XML file
$xml = simplexml_load_file('Varahaldussustem.xml');

// Check if the XML file was loaded successfully
if ($xml === false) {
    echo "Failed to load XML file.";
    foreach (libxml_get_errors() as $error) {
        echo "<br>", $error->message;
    }
    exit;
}

// Function 1: Filter assets by responsible person
function filterByResponsiblePerson($xml, $personName) {
    $results = [];
    foreach ($xml->vara as $vara) {
        if ((string)$vara->details->vastutaja === $personName) {
            $results[] = [
                'varanumber' => (string)$vara->details->varanumber,
                'nimetus' => (string)$vara->details->nimetus,
                'seisund' => (string)$vara->details->seisund,
                'maksmus' => (string)$vara->details->maksmus,
                'lisaaeg' => (string)$vara->details->lisaaeg,
            ];
        }
    }

    echo "<h2>Assets for Responsible Person: $personName</h2>";
    if (empty($results)) {
        echo "No assets found for this person.";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Varanumber</th><th>Nimetus</th><th>Seisund</th><th>Maksmus</th><th>Lisa Aeg</th></tr>";
        foreach ($results as $item) {
            echo "<tr>";
            echo "<td>{$item['varanumber']}</td>";
            echo "<td>{$item['nimetus']}</td>";
            echo "<td>{$item['seisund']}</td>";
            echo "<td>{$item['maksmus']}</td>";
            echo "<td>{$item['lisaaeg']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// Function 2: Calculate total value of all assets
function calculateTotalValue($xml) {
    $totalValue = 0;
    foreach ($xml->vara as $vara) {
        $totalValue += (int)$vara->details->maksmus;
    }

    echo "<h2>Total Value of All Assets: $totalValue</h2>";
}

// Run the functions
echo "<h1>Results</h1>";
filterByResponsiblePerson($xml, 'Irina Merkulova'); // Replace with the name of the responsible person
calculateTotalValue($xml);

?>
