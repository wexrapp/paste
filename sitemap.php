<?php
// Import config.php
require_once("config.php");

// Create a new XML document
$xml = new DOMDocument("1.0", "UTF-8");

// Create the root <urlset> element
$urlset = $xml->createElement("urlset");
$urlset->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
$xml->appendChild($urlset);

// Function to sanitize URLs
function sanitize_url($url) {
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
}

// Fetch pastes from the database
$sql = "SELECT `id`, `create_time` FROM `pastes`";
if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        // Build the paste URL
        $paste_id = sanitize_url($row['id']);
        $paste_url = "https://paste.wexr.tech/{$paste_id}";

        // Format the create_time in W3C datetime format
        $create_time = date("c", strtotime($row['create_time']));

        // Create the <url> element and its child elements
        $url = $xml->createElement("url");
        $loc = $xml->createElement("loc", $paste_url);
        $lastmod = $xml->createElement("lastmod", $create_time);

        // Append the child elements to the <url> element
        $url->appendChild($loc);
        $url->appendChild($lastmod);

        // Append the <url> element to the root <urlset> element
        $urlset->appendChild($url);
    }
    $result->free();
}

// Save the XML document to a file named sitemap.xml
$xml->formatOutput = true;
$xml->save("sitemap.xml");

echo "Sitemap generated successfully and saved as sitemap.xml.";
?>
