<?php
// Quick UI tests for role behavior in OrderController::index()
require __DIR__ . '/../vendor/autoload.php';
session_start();

use App\Controllers\OrderController;
use App\Core\Request;
use App\Services\Orders\GetOrderListService;

// Simple fake service that returns two orders: one assigned to mechanic user_id=2, one to user_id=3
class FakeOrderListService extends GetOrderListService
{
    public function __construct() {}

    public function execute(array $filters = []) : array
    {
        $all = [
            ['id' => 10, 'customer_name' => 'Alice', 'vehicle_model' => 'Civic', 'total' => 150.0, 'status' => 'PENDING', 'created_at' => '2026-06-02 10:00:00', 'mechanic_user_id' => 2],
            ['id' => 11, 'customer_name' => 'Bob', 'vehicle_model' => 'Corolla', 'total' => 200.0, 'status' => 'IN_PROGRESS', 'created_at' => '2026-06-01 12:00:00', 'mechanic_user_id' => 3],
        ];

        if (!empty($filters['mechanic_user_id'])) {
            $uid = (int) $filters['mechanic_user_id'];
            return array_values(array_filter($all, fn($o) => ($o['mechanic_user_id'] ?? 0) === $uid));
        }

        return $all;
    }
}

// Helper to inject private property via reflection
function inject(object $target, string $prop, $value): void
{
    $r = new ReflectionObject($target);
    while (!$r->hasProperty($prop) && $r->getParentClass()) {
        $r = $r->getParentClass();
    }
    $p = $r->getProperty($prop);
    $p->setAccessible(true);
    $p->setValue($target, $value);
}

// Create a real Request object but ensure $_GET is empty
$_GET = [];
$request = new Request();

// Instantiate controller without running constructor to avoid DB/bootstrap side-effects
$rc = new ReflectionClass(OrderController::class);
$controller = $rc->newInstanceWithoutConstructor();
// inject fake service
inject($controller, 'getOrderListService', new FakeOrderListService());

// Test as Attendant
$_SESSION['user'] = ['id' => 1, 'name' => 'Attendant User', 'role' => 'Attendant'];
$htmlAttendant = $controller->index($request);
$hasCreate = strpos($htmlAttendant, '/orders/create') !== false;
$hasAlert = strpos($htmlAttendant, 'Showing only service orders assigned to you.') !== false;

// Test as Mechanic (user id 2)
$_SESSION['user'] = ['id' => 2, 'name' => 'Mechanic User', 'role' => 'Mechanic'];
$htmlMechanic = $controller->index($request);
$hasCreateMech = strpos($htmlMechanic, '/orders/create') !== false;
$hasAlertMech = strpos($htmlMechanic, 'Showing only service orders assigned to you.') !== false;

// Output concise results
echo "Attendant: create_link=" . ($hasCreate ? 'YES' : 'NO') . ", alert=" . ($hasAlert ? 'YES' : 'NO') . "\n";
echo "Mechanic: create_link=" . ($hasCreateMech ? 'YES' : 'NO') . ", alert=" . ($hasAlertMech ? 'YES' : 'NO') . "\n";

// Also show snippet of table rows count for each
preg_match_all('#<tr>\s*<td><strong>#', $htmlAttendant, $mA);
preg_match_all('#<tr>\s*<td><strong>#', $htmlMechanic, $mM);
$rowsA = count($mA[0]);
$rowsM = count($mM[0]);

echo "Attendant rows: $rowsA\n";
echo "Mechanic rows: $rowsM\n";

// Save outputs to tmp files for manual inspection
file_put_contents(__DIR__ . '/out_attendant.html', $htmlAttendant);
file_put_contents(__DIR__ . '/out_mechanic.html', $htmlMechanic);

return 0;
