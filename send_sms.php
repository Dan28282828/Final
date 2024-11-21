<?php
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require __DIR__ . "/vendor/autoload.php";

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "safe_haven"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$base_url = "ped3kl.api.infobip.com";
$api_key = "b5b425ff9ecae7eee945ab99f46ced95-d23f6bc8-b275-49ce-a019-e219f8d2e272";

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['latitude']) || !isset($input['longitude'])) {
        throw new Exception("Location data is missing.");
    }

    $latitude = $input['latitude'];
    $longitude = $input['longitude'];
    $phone_number = "+639665534768"; 

    $stmt = $conn->prepare("INSERT INTO sos_reports (latitude, longitude, phone_number) VALUES (?, ?, ?)");
    $stmt->bind_param("dss", $latitude, $longitude, $phone_number);
    $stmt->execute();
    $stmt->close();

    $sos_message = "Emergency! SOS alert triggered. Location: https://www.google.com/maps?q=$latitude,$longitude";

    $configuration = new Configuration($base_url, apiKey: $api_key);
    $api = new SmsApi(config: $configuration);

    $destination = new SmsDestination(to: $phone_number);
    $message = new SmsTextualMessage(
        destinations: [$destination],
        text: $sos_message
    );
    $request = new SmsAdvancedTextualRequest(messages: [$message]);

    $response = $api->sendSmsMessage($request);

    echo json_encode(["success" => true, "message" => "SOS alert sent successfully."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Failed to send SOS alert: " . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
