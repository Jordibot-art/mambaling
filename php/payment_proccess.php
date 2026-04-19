<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['doc_id'])) {
    header("Location: login.html");
    exit;
}

$username = $_SESSION['username'];
$doc_id = (int) $_GET['doc_id'];

$conn = new mysqli('localhost', 'root', '', 'mambaling');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT id, first_name, last_name FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Error: User not found in the database.");
}

$user_id = $user['id'];
$full_name = $user['first_name'] . " " . $user['last_name'];

$query = "SELECT document_type, amount FROM special_documents WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doc_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    die("Error: Document not found.");
}

$document_type = $row['document_type'] ?? 'Unknown';
$amount = 500;

switch ($document_type) {
    case 'Cedula':
        $amount = 100;
        break;
    case 'Manhole':
    case 'Building Permit':
    case 'Himo Tabay':
    case 'Himo Poso':
        $amount = 500;
        break;
}

$api_key = "sk_test_PgQSW8VS5sTRoPEjxC3KBgJK";
$data = [
    "data" => [
        "attributes" => [
            "amount" => $amount * 100,
            "description" => "Payment for $document_type",
            "remarks" => "Payment from: $full_name",
            "currency" => "PHP",
            "metadata" => [
                "doc_id" => $doc_id
            ]
        ]
    ]
];

$ch = curl_init("https://api.paymongo.com/v1/links");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic " . base64_encode($api_key . ":"), 
    "Content-Type: application/json",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (isset($response['data']['attributes']['checkout_url'])) {
    $checkout_url = $response['data']['attributes']['checkout_url'];

    $sql = "INSERT INTO tbltransactions (user_id, first_name, last_name, document_type, amount, status, created_at) 
    VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssd", $user_id, $user['first_name'], $user['last_name'], $document_type, $amount);
    $stmt->execute();
    $stmt->close();

    header("Location: $checkout_url");
    exit;
} else {
    echo "Error: " . json_encode($response, JSON_PRETTY_PRINT);
}

$conn->close();
?>
