







Here’s a clean, service-oriented BalanceTransferController for allowing users to transfer balance to another user’s account. This version delegates business logic to a BalanceTransferService, uses CSRF protection, and keeps views and models cleanly separated.

⸻

BalanceTransferController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\CsrfHelper;
use App\Models\User;
use App\Services\Wallet\BalanceTransferService;

class BalanceTransferController extends Controller
{
    protected BalanceTransferService $balanceTransferService;

    public function __construct(BalanceTransferService $balanceTransferService)
    {
        parent::__construct();
        $this->balanceTransferService = $balanceTransferService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Buyer view path
    }

    public function index(): void
    {
        $user = $this->getUser();

        $this->renderBuyerTemplate('wallet/transfer', [
            'user'        => $user,
            'page_title'  => 'Transfer Balance',
            'csrf_token'  => CsrfHelper::generateToken()
        ]);
    }

    public function send(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
            return;
        }

        if (!CsrfHelper::isValidToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
            return;
        }

        $user = $this->getUser();

        $result = $this->balanceTransferService->transferToUser($user, $_POST['recipient_username'] ?? '', $_POST['amount'] ?? 0);

        echo json_encode($result);
    }
}



⸻

Expected BalanceTransferService Methods

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;

class BalanceTransferService
{
    public function transferToUser(User $sender, string $recipientUsername, float $amount): array
    {
        // Validate amount
        // Find recipient
        // Check sender balance
        // Perform DB transaction
        // Log transfers
        // Return success/failure with message
    }
}



⸻

Example Logic for transferToUser()

public function transferToUser(User $sender, string $recipientUsername, float $amount): array
{
    if ($amount <= 0) {
        return ['success' => false, 'error' => 'Invalid transfer amount.'];
    }

    $recipient = User::findByUsername($recipientUsername);
    if (! $recipient || $recipient->id === $sender->id) {
        return ['success' => false, 'error' => 'Invalid recipient.'];
    }

    if ($sender->balance < $amount) {
        return ['success' => false, 'error' => 'Insufficient balance.'];
    }

    // Proceed with atomic transfer
    $db = \Core\Database::getInstance();
    $db->beginTransaction();

    try {
        Wallet::deduct($sender->id, $amount);
        Wallet::credit($recipient->id, $amount);
        Wallet::logTransfer($sender->id, $recipient->id, $amount);

        $db->commit();
        return ['success' => true, 'message' => 'Balance transferred successfully.'];
    } catch (\Exception $e) {
        $db->rollBack();
        return ['success' => false, 'error' => 'Transfer failed: ' . $e->getMessage()];
    }
}



⸻

Sample Wallet Model Methods

namespace App\Models;

use Core\Database;

class Wallet
{
    public static function deduct(int $userId, float $amount): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $userId]);
    }

    public static function credit(int $userId, float $amount): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $userId]);
    }

    public static function logTransfer(int $fromId, int $toId, float $amount): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO balance_transfers (sender_id, recipient_id, amount, transferred_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$fromId, $toId, $amount]);
    }
}



⸻

Let me know if you want help with:
	•	The actual transfer form view (wallet/transfer.php)
	•	DB migration for the balance_transfers table
	•	Creating UI feedback for success/error

Ready to wire it up when you are.
Here’s a full refactored ReferralController that delegates logic to a ReferralService, uses proper models, and assumes your app uses a Core\Database structure for the DB connection. This aligns with your MVC and service-model separation pattern.

⸻

ReferralController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\CsrfHelper;
use App\Models\User;
use App\Services\Referral\ReferralService;

class ReferralController extends Controller
{
    protected ReferralService $referralService;

    public function __construct(ReferralService $referralService)
    {
        parent::__construct();
        $this->referralService = $referralService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Buyer view path
    }

    public function index(): void
    {
        $user = $this->getUser();
        $referralData = $this->referralService->getReferralOverview($user);

        $this->renderBuyerTemplate('referral/index', array_merge($referralData, [
            'user'        => $user,
            'page_title'  => 'Referral Program',
            'csrf_token'  => CsrfHelper::generateToken(),
        ]));
    }

    public function withdraw(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Invalid method']);
            return;
        }

        if (!CsrfHelper::isValidToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            return;
        }

        $user = $this->getUser();
        $result = $this->referralService->requestWithdrawal($user);

        echo json_encode($result);
    }
}



⸻

Expected ReferralService Interface

namespace App\Services\Referral;

use App\Models\User;

class ReferralService
{
    public function getReferralOverview(User $user): array
    {
        // Fetch referral stats, referred users, balances, etc.
    }

    public function requestWithdrawal(User $user): array
    {
        // Process a withdrawal request
    }
}



⸻

Example Referral Model

namespace App\Models;

use Core\Database;

class Referral
{
    public static function findByReferrerId(int $userId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM referrals WHERE referrer_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function getEarnings(int $userId): float
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT SUM(amount) FROM referral_earnings WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (float) $stmt->fetchColumn();
    }
}



⸻

Database Connection (Core/Database.php)

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';

            try {
                self::$instance = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}



⸻

config/database.php

return [
    'dsn'      => 'mysql:host=localhost;dbname=your_db_name;charset=utf8mb4',
    'username' => 'your_db_user',
    'password' => 'your_db_pass',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];



⸻

Let me know if you’d like help building the ReferralService logic or views for this module.
Here’s a full refactored ReferralController that delegates logic to a ReferralService, uses proper models, and assumes your app uses a Core\Database structure for the DB connection. This aligns with your MVC and service-model separation pattern.

⸻

ReferralController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\CsrfHelper;
use App\Models\User;
use App\Services\Referral\ReferralService;

class ReferralController extends Controller
{
    protected ReferralService $referralService;

    public function __construct(ReferralService $referralService)
    {
        parent::__construct();
        $this->referralService = $referralService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Buyer view path
    }

    public function index(): void
    {
        $user = $this->getUser();
        $referralData = $this->referralService->getReferralOverview($user);

        $this->renderBuyerTemplate('referral/index', array_merge($referralData, [
            'user'        => $user,
            'page_title'  => 'Referral Program',
            'csrf_token'  => CsrfHelper::generateToken(),
        ]));
    }

    public function withdraw(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Invalid method']);
            return;
        }

        if (!CsrfHelper::isValidToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            return;
        }

        $user = $this->getUser();
        $result = $this->referralService->requestWithdrawal($user);

        echo json_encode($result);
    }
}



⸻

Expected ReferralService Interface

namespace App\Services\Referral;

use App\Models\User;

class ReferralService
{
    public function getReferralOverview(User $user): array
    {
        // Fetch referral stats, referred users, balances, etc.
    }

    public function requestWithdrawal(User $user): array
    {
        // Process a withdrawal request
    }
}



⸻

Example Referral Model

namespace App\Models;

use Core\Database;

class Referral
{
    public static function findByReferrerId(int $userId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM referrals WHERE referrer_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function getEarnings(int $userId): float
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT SUM(amount) FROM referral_earnings WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (float) $stmt->fetchColumn();
    }
}



⸻

Database Connection (Core/Database.php)

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';

            try {
                self::$instance = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}



⸻

config/database.php

return [
    'dsn'      => 'mysql:host=localhost;dbname=your_db_name;charset=utf8mb4',
    'username' => 'your_db_user',
    'password' => 'your_db_pass',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];



⸻

Let me know if you’d like help building the ReferralService logic or views for this module.
Here’s how you can move the logic from the index() and reportOrder() controller methods into a service class, keeping your controller clean and delegating business logic appropriately.

⸻

1. Refactored Controller:

use App\Services\Buyer\OrderService;

class OrderController
{
    protected OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->orderService->getOrderOverviewData($user);

        $this->renderBuyerTemplate('orders/index', $data);
    }

    public function reportOrder(): void
    {
        $response = $this->orderService->reportOrder();

        http_response_code($response['code']);
        unset($response['code']);
        echo json_encode($response);
    }
}



⸻

2. New OrderService Class:

namespace App\Services\Buyer;

use App\Models\Orders;
use App\Helpers\CsrfHelper;
use App\Session;

class OrderService
{
    public function getOrderOverviewData($user): array
    {
        return [
            'user'            => $user,
            'orders'          => Orders::findByBuyer($user->username),
            'allOrdersCount'  => Orders::countAllOrders(),
            'completedCount'  => Orders::countCompletedOrders(),
            'reportedCount'   => Orders::countReportedOrders(),
            'rejectedCount'   => Orders::countRejectedOrders(),
            'csrf_token'      => CsrfHelper::generateToken(),
            'bearer_token'    => Session::get('api_token'),
            'page_title'      => 'My Orders',
        ];
    }

    public function reportOrder(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['order_id'])) {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Invalid request',
            ];
        }

        $userId = Session::get('user_id');
        if (! $userId) {
            return [
                'code'    => 403,
                'success' => false,
                'error'   => 'Unauthorized',
            ];
        }

        $orderId = (int) $_POST['order_id'];
        $order   = Orders::findByIdAndBuyer($orderId, $userId);

        if (! $order) {
            return [
                'code'    => 404,
                'success' => false,
                'error'   => 'Order not found',
            ];
        }

        if (Orders::markAsReported($orderId)) {
            return [
                'code'    => 200,
                'success' => true,
                'message' => 'Order reported successfully',
            ];
        }

        return [
            'code'    => 500,
            'success' => false,
            'error'   => Orders::getLastError() ?? 'Reporting failed',
        ];
    }
}



⸻

Let me know if you want the Orders model methods like countAllOrders() or markAsReported() cleaned up or moved into a repository class as well.
Here’s the properly refactored version of your OrderController using a clean service-oriented approach, following your structure and conventions. This includes using OrderService, setting the views path, and keeping the controller slim.

⸻

Refactored OrderController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\CsrfHelper;
use App\Services\Buyer\OrderService;
use App\Session;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->template->setViewsPath(__DIR__ . '/../Views');
        $this->orderService = new OrderService();
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->orderService->getOrderOverviewData($user);

        $this->renderBuyerTemplate('orders/index', $data);
    }

    public function reportOrder(): void
    {
        $response = $this->orderService->reportOrder();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

New OrderService.php

Create this in App\Services\Buyer\OrderService.php:

<?php

namespace App\Services\Buyer;

use App\Models\Orders;
use App\Helpers\CsrfHelper;
use App\Session;

class OrderService
{
    public function getOrderOverviewData($user): array
    {
        return [
            'user'            => $user,
            'orders'          => Orders::findByBuyer($user->username),
            'allOrdersCount'  => Orders::countAllOrders(),
            'completedCount'  => Orders::countCompletedOrders(),
            'reportedCount'   => Orders::countReportedOrders(),
            'rejectedCount'   => Orders::countRejectedOrders(),
            'csrf_token'      => CsrfHelper::generateToken(),
            'bearer_token'    => Session::get('api_token'),
            'page_title'      => 'My Orders',
        ];
    }

    public function reportOrder(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['order_id'])) {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Invalid request',
            ];
        }

        $userId = Session::get('user_id');
        if (! $userId) {
            return [
                'code'    => 403,
                'success' => false,
                'error'   => 'Unauthorized',
            ];
        }

        $orderId = (int) $_POST['order_id'];
        $order   = Orders::findByIdAndBuyer($orderId, $userId);

        if (! $order) {
            return [
                'code'    => 404,
                'success' => false,
                'error'   => 'Order not found',
            ];
        }

        if (Orders::markAsReported($orderId)) {
            return [
                'code'    => 200,
                'success' => true,
                'message' => 'Order reported successfully',
            ];
        }

        return [
            'code'    => 500,
            'success' => false,
            'error'   => Orders::getLastError() ?? 'Reporting failed',
        ];
    }
}



⸻

This keeps your controller clean and business logic isolated in the service. Let me know if you’d like to also refactor the Orders model calls into a repository or make it more testable.

Here’s a cleaned-up and corrected version of your AddBalanceController:

Issues Fixed:
	•	The injected service is named BalanceService, but the property was declared as AddBalanceService.
	•	Variable name mismatch: $balanceData vs. $addbalanceData.
	•	Type declaration on the property should match the imported service.

⸻

Fixed AddBalanceController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;
use App\Services\Wallet\BalanceService;

class AddBalanceController extends Controller
{
    protected BalanceService $balanceService;

    public function __construct(BalanceService $balanceService)
    {
        parent::__construct();
        $this->balanceService = $balanceService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Custom views path for Wallet
    }

    public function index(): void 
    {
        $user = $this->getUser();

        $balanceData = $this->balanceService->getData($user, $this->tableConfig);

        $data = array_merge($balanceData, [
            'page_title' => 'Add Balance',
            'user'       => $user,
        ]);

        $this->renderBuyerTemplate('add-balance/index', $data);
    }
}



⸻

Let me know if you’d like the corresponding BalanceService stubbed out or want to apply the same structure to other wallet-related controllers.
Got it — you’re asking to rename everything from BalanceService to AddBalanceService for clarity and consistency with AddBalanceController.

Here’s the properly modified AddBalanceController with consistent naming and a matching AddBalanceService:

⸻

Modified AddBalanceController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;
use App\Services\Wallet\AddBalanceService;

class AddBalanceController extends Controller
{
    protected AddBalanceService $addBalanceService;

    public function __construct(AddBalanceService $addBalanceService)
    {
        parent::__construct();
        $this->addBalanceService = $addBalanceService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Custom views path for Wallet
    }

    public function index(): void 
    {
        $user = $this->getUser();

        $addBalanceData = $this->addBalanceService->getData($user, $this->tableConfig);

        $data = array_merge($addBalanceData, [
            'page_title' => 'Add Balance',
            'user'       => $user,
        ]);

        $this->renderBuyerTemplate('add-balance/index', $data);
    }
}



⸻

Stub for AddBalanceService.php

(Place under App\Services\Wallet\AddBalanceService.php if not already created)

<?php

namespace App\Services\Wallet;

use App\Models\User;

class AddBalanceService
{
    public function getData(User $user, array $tableConfig): array
    {
        // Replace this with your actual logic
        return [
            'wallet_balance' => 100.00, // example static value
            'table_config'   => $tableConfig,
        ];
    }
}



⸻

Let me know if you also want to move wallet-related logic (e.g. transaction history or funding methods) into this service.
Sure — to modify your ProfileController properly, I just need to know what kind of modification you want:
	•	Refactor it to use a service (e.g. ProfileService)?
	•	Add view rendering for a profile page?
	•	Handle profile updates (e.g. change email, password)?
	•	Clean up and align it with your existing controller/service structure?

If you can share the current code for ProfileController, I’ll make the adjustments you need. Otherwise, here’s a clean, service-based template assuming you want to show and update a user profile:

⸻

Sample Modified ProfileController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Buyer\ProfileService;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct()
    {
        parent::__construct();
        $this->profileService = new ProfileService();
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->profileService->getProfileData($user);

        $this->renderBuyerTemplate('profile/index', [
            'user'        => $user,
            'page_title'  => 'My Profile',
            ...$data
        ]);
    }

    public function update(): void
    {
        $response = $this->profileService->updateProfile();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

Example ProfileService.php

<?php

namespace App\Services\Buyer;

use App\Models\User;
use App\Session;

class ProfileService
{
    public function getProfileData(User $user): array
    {
        return [
            'email'     => $user->email,
            'full_name' => $user->full_name,
            // add more fields if needed
        ];
    }

    public function updateProfile(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['code' => 400, 'success' => false, 'error' => 'Invalid request'];
        }

        $userId = Session::get('user_id');
        if (! $userId) {
            return ['code' => 403, 'success' => false, 'error' => 'Unauthorized'];
        }

        // Example validation and update logic
        $email = $_POST['email'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['code' => 422, 'success' => false, 'error' => 'Invalid email'];
        }

        if (User::updateEmail($userId, $email)) {
            return ['code' => 200, 'success' => true, 'message' => 'Profile updated'];
        }

        return ['code' => 500, 'success' => false, 'error' => 'Update failed'];
    }
}



⸻

Let me know if you want this to hook into password changing, avatar uploading, or anything else. Just drop your existing controller if you want this refactor applied to your current code.Great — here’s a full refactored BecomeSellerController, aligned with your usual architecture: it uses a BecomeSellerService, follows clean controller practices, and renders a buyer-facing view.

⸻

Refactored BecomeSellerController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Buyer\BecomeSellerService;

class BecomeSellerController extends Controller
{
    protected BecomeSellerService $becomeSellerService;

    public function __construct()
    {
        parent::__construct();
        $this->becomeSellerService = new BecomeSellerService();
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Adjust path if needed
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->becomeSellerService->getViewData($user);

        $this->renderBuyerTemplate('become-seller/index', [
            'user'        => $user,
            'page_title'  => 'Become a Seller',
            ...$data
        ]);
    }

    public function submit(): void
    {
        $response = $this->becomeSellerService->submitRequest();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

Example BecomeSellerService.php

<?php

namespace App\Services\Buyer;

use App\Session;
use App\Models\User;

class BecomeSellerService
{
    public function getViewData($user): array
    {
        return [
            'is_seller' => $user->is_seller ?? false,
        ];
    }

    public function submitRequest(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['code' => 400, 'success' => false, 'error' => 'Invalid request method'];
        }

        $userId = Session::get('user_id');
        if (! $userId) {
            return ['code' => 403, 'success' => false, 'error' => 'Unauthorized'];
        }

        // Here you could add real validation, DB update, notifications, etc.
        if (User::applyToBecomeSeller($userId)) {
            return ['code' => 200, 'success' => true, 'message' => 'Application submitted'];
        }

        return ['code' => 500, 'success' => false, 'error' => 'Failed to submit application'];
    }
}



⸻

Optional View: Views/become-seller/index.php

Add a form here that POSTs to /become-seller/submit or your route.

⸻

Let me know if you want the User::applyToBecomeSeller() method stubbed out or database schema additions for seller applications!






Absolutely — here’s your clean and refactored ReportController, matching your current service-based structure (as with OrderController, AddBalanceController, etc.). It uses a ReportService, clean method separation, and supports both viewing and submitting reports.

⸻

Refactored ReportController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\CsrfHelper;
use App\Services\Buyer\ReportService;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct()
    {
        parent::__construct();
        $this->reportService = new ReportService();
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->reportService->getReportOverviewData($user);

        $this->renderBuyerTemplate('reports/index', $data);
    }

    public function submit(): void
    {
        $response = $this->reportService->submitReport();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

ReportService.php (under App\Services\Buyer)

If you haven’t made one yet, here’s a solid base for the ReportService:

<?php

namespace App\Services\Buyer;

use App\Helpers\CsrfHelper;
use App\Models\Reports;
use App\Models\Orders;
use App\Session;

class ReportService
{
    public function getReportOverviewData($user): array
    {
        return [
            'user'            => $user,
            'reports'         => Reports::findByBuyer($user->username),
            'pendingCount'    => Reports::countByStatus($user->username, 'pending'),
            'reviewedCount'   => Reports::countByStatus($user->username, 'reviewed'),
            'rejectedCount'   => Reports::countByStatus($user->username, 'rejected'),
            'allCount'        => Reports::countByUser($user->username),
            'csrf_token'      => CsrfHelper::generateToken(),
            'bearer_token'    => Session::get('api_token'),
            'page_title'      => 'My Reports',
        ];
    }

    public function submitReport(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['order_id'])) {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Invalid request',
            ];
        }

        $userId = Session::get('user_id');
        if (! $userId) {
            return [
                'code'    => 403,
                'success' => false,
                'error'   => 'Unauthorized',
            ];
        }

        $orderId = (int) $_POST['order_id'];
        $order   = Orders::findByIdAndBuyer($orderId, $userId);

        if (! $order) {
            return [
                'code'    => 404,
                'success' => false,
                'error'   => 'Order not found',
            ];
        }

        if (Orders::markAsReported($orderId)) {
            return [
                'code'    => 200,
                'success' => true,
                'message' => 'Order reported successfully',
            ];
        }

        return [
            'code'    => 500,
            'success' => false,
            'error'   => Orders::getLastError() ?? 'Reporting failed',
        ];
    }
}



⸻

Let me know if you’d like a version that supports admin viewing of reports too (e.g. AdminReportController) or if you’re consolidating logic under a MainService.
Here’s a clean refactored TicketController, following the same structure you’ve been using: service-based logic (TicketService), clean controller actions, and buyer-facing templating.

⸻

Refactored TicketController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Buyer\TicketService;

class TicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct()
    {
        parent::__construct();
        $this->ticketService = new TicketService();
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->ticketService->getTicketOverviewData($user);

        $this->renderBuyerTemplate('tickets/index', [
            'user'        => $user,
            'page_title'  => 'My Tickets',
            ...$data
        ]);
    }

    public function submit(): void
    {
        $response = $this->ticketService->submitTicket();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

Sample TicketService.php (under App\Services\Buyer)

<?php

namespace App\Services\Buyer;

use App\Models\Tickets;
use App\Helpers\CsrfHelper;
use App\Session;

class TicketService
{
    public function getTicketOverviewData($user): array
    {
        return [
            'tickets'        => Tickets::findByUser($user->id),
            'openCount'      => Tickets::countByStatus($user->id, 'open'),
            'closedCount'    => Tickets::countByStatus($user->id, 'closed'),
            'allCount'       => Tickets::countByUser($user->id),
            'csrf_token'     => CsrfHelper::generateToken(),
            'bearer_token'   => Session::get('api_token'),
        ];
    }

    public function submitTicket(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['subject']) || empty($_POST['message'])) {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Missing required fields',
            ];
        }

        $userId = Session::get('user_id');
        if (! $userId) {
            return [
                'code'    => 403,
                'success' => false,
                'error'   => 'Unauthorized',
            ];
        }

        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);

        $ticketId = Tickets::create($userId, $subject, $message);

        if ($ticketId) {
            return [
                'code'    => 200,
                'success' => true,
                'message' => 'Ticket submitted successfully',
                'ticket_id' => $ticketId,
            ];
        }

        return [
            'code'    => 500,
            'success' => false,
            'error'   => Tickets::getLastError() ?? 'Failed to submit ticket',
        ];
    }
}



⸻

Let me know if:
	•	You’d like to support replies (chat-style),
	•	You want an admin-side TicketController,
	•	Or you’re merging this into a unified MainService like you did for reports.
Certainly! Below is the refactored AddBalanceController that uses a service-based approach with a dedicated AddBalanceService, following your existing controller structure.

⸻

Refactored AddBalanceController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Wallet\AddBalanceService;

class AddBalanceController extends Controller
{
    protected AddBalanceService $addBalanceService;

    public function __construct(AddBalanceService $addBalanceService)
    {
        parent::__construct();
        $this->addBalanceService = $addBalanceService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Custom views path for Wallet
    }

    public function index(): void 
    {
        $user = $this->getUser();

        $balanceData = $this->addBalanceService->getBalanceData($user);

        $this->renderBuyerTemplate('wallet/add-balance/index', [
            'user'        => $user,
            'page_title'  => 'Add Balance',
            ...$balanceData
        ]);
    }

    public function submit(): void
    {
        $response = $this->addBalanceService->processBalanceAddition();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

Example AddBalanceService.php

<?php

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Session;

class AddBalanceService
{
    public function getBalanceData(User $user): array
    {
        // Return the user's current wallet balance and other relevant data
        return [
            'current_balance' => Wallet::getBalanceByUserId($user->id),
            'available_balance' => Wallet::getAvailableBalanceByUserId($user->id),
        ];
    }

    public function processBalanceAddition(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['amount'])) {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Amount is required',
            ];
        }

        $userId = Session::get('user_id');
        if (!$userId) {
            return [
                'code'    => 403,
                'success' => false,
                'error'   => 'Unauthorized',
            ];
        }

        $amount = (float) $_POST['amount'];
        if ($amount <= 0) {
            return [
                'code'    => 422,
                'success' => false,
                'error'   => 'Invalid amount',
            ];
        }

        // Assume the logic for adding balance is in the `Wallet::addBalance()` method
        if (Wallet::addBalance($userId, $amount)) {
            return [
                'code'    => 200,
                'success' => true,
                'message' => 'Balance added successfully',
            ];
        }

        return [
            'code'    => 500,
            'success' => false,
            'error'   => Wallet::getLastError() ?? 'Failed to add balance',
        ];
    }
}



⸻

Optional Wallet::addBalance() Model Method Example:

If you don’t already have it, you can implement a simple method for adding balance in the Wallet model:

<?php

namespace App\Models;

use App\Database;

class Wallet
{
    public static function addBalance(int $userId, float $amount): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
        $stmt->bind_param('di', $amount, $userId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public static function getBalanceByUserId(int $userId): float
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT balance FROM wallets WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['balance'] ?? 0;
    }

    public static function getAvailableBalanceByUserId(int $userId): float
    {
        // Add additional logic here if you want to differentiate available balance (e.g., locked funds)
        return self::getBalanceByUserId($userId);
    }
}



⸻

Let me know if you’d like to handle additional cases, like different payment gateways, user input validations, or custom error messages!
Sure! Below is the refactored BecomeSellerController, using a service-based approach and following the structure you’ve used with other controllers, such as AddBalanceController and TicketController.

⸻

Refactored BecomeSellerController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Buyer\BecomeSellerService;

class BecomeSellerController extends Controller
{
    protected BecomeSellerService $becomeSellerService;

    public function __construct(BecomeSellerService $becomeSellerService)
    {
        parent::__construct();
        $this->becomeSellerService = $becomeSellerService;
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->becomeSellerService->getSellerData($user);

        $this->renderBuyerTemplate('account/become-seller/index', [
            'user'        => $user,
            'page_title'  => 'Become a Seller',
            ...$data
        ]);
    }

    public function submitRequest(): void
    {
        $response = $this->becomeSellerService->submitSellerRequest();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

Example BecomeSellerService.php (under App\Services\Buyer)

<?php

namespace App\Services\Buyer;

use App\Models\User;
use App\Models\SellerRequest;
use App\Session;

class BecomeSellerService
{
    public function getSellerData(User $user): array
    {
        // Example: Check if the user is already a seller or if a request is pending
        $isSeller = $user->isSeller(); // You may have a method in your User model to check this
        $pendingRequest = SellerRequest::isPendingRequest($user->id);

        return [
            'is_seller'         => $isSeller,
            'pending_request'   => $pendingRequest,
            'csrf_token'        => CsrfHelper::generateToken(),
        ];
    }

    public function submitSellerRequest(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Invalid request method',
            ];
        }

        $userId = Session::get('user_id');
        if (!$userId) {
            return [
                'code'    => 403,
                'success' => false,
                'error'   => 'Unauthorized',
            ];
        }

        // You could add other validation for becoming a seller here (e.g., user’s balance, etc.)

        if (SellerRequest::submitRequest($userId)) {
            return [
                'code'    => 200,
                'success' => true,
                'message' => 'Your request to become a seller has been submitted successfully.',
            ];
        }

        return [
            'code'    => 500,
            'success' => false,
            'error'   => SellerRequest::getLastError() ?? 'Failed to submit request',
        ];
    }
}



⸻

Example SellerRequest Model Method

If you don’t have a model or methods like isPendingRequest() or submitRequest(), here’s an example of how you might structure them:

<?php

namespace App\Models;

use App\Database;

class SellerRequest
{
    public static function isPendingRequest(int $userId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM seller_requests WHERE user_id = ? AND status = 'pending'");
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return !empty($result);
    }

    public static function submitRequest(int $userId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO seller_requests (user_id, status) VALUES (?, 'pending')");
        $stmt->bind_param('i', $userId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}



⸻

Additional Considerations:
	1.	Validation: You might want to add further validation checks to ensure the user meets criteria for becoming a seller (e.g., sufficient balance, profile completion, etc.).
	2.	Seller Approval Flow: If there’s an admin or moderation step before approval, the system could store a status field (pending, approved, rejected) in seller_requests.
	3.	Notification: You can implement notifications to the user once their seller application has been approved or rejected.

⸻

Let me know if you’d like to adjust any additional specifics, or if you want to integrate this with other systems like email notifications or admin approvals!
Here’s the refactored BecomePremiumController, following the same service-based approach as the previous controllers, while focusing on a user becoming a premium member.

⸻

Refactored BecomePremiumController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Buyer\BecomePremiumService;

class BecomePremiumController extends Controller
{
    protected BecomePremiumService $becomePremiumService;

    public function __construct(BecomePremiumService $becomePremiumService)
    {
        parent::__construct();
        $this->becomePremiumService = $becomePremiumService;
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        $user = $this->getUser();

        $data = $this->becomePremiumService->getPremiumData($user);

        $this->renderBuyerTemplate('account/become-premium/index', [
            'user'        => $user,
            'page_title'  => 'Become a Premium Member',
            ...$data
        ]);
    }

    public function submitRequest(): void
    {
        $response = $this->becomePremiumService->submitPremiumRequest();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

Example BecomePremiumService.php (under App\Services\Buyer)

<?php

namespace App\Services\Buyer;

use App\Models\User;
use App\Models\PremiumRequest;
use App\Session;

class BecomePremiumService
{
    public function getPremiumData(User $user): array
    {
        // Example: Check if the user is already a premium member or if a request is pending
        $isPremium = $user->isPremium(); // You may have a method in your User model to check this
        $pendingRequest = PremiumRequest::isPendingRequest($user->id);

        return [
            'is_premium'        => $isPremium,
            'pending_request'   => $pendingRequest,
            'csrf_token'        => CsrfHelper::generateToken(),
        ];
    }

    public function submitPremiumRequest(): array
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Invalid request method',
            ];
        }

        $userId = Session::get('user_id');
        if (!$userId) {
            return [
                'code'    => 403,
                'success' => false,
                'error'   => 'Unauthorized',
            ];
        }

        // You could add other validation for becoming a premium member here (e.g., user’s balance, etc.)

        if (PremiumRequest::submitRequest($userId)) {
            return [
                'code'    => 200,
                'success' => true,
                'message' => 'Your request to become a premium member has been submitted successfully.',
            ];
        }

        return [
            'code'    => 500,
            'success' => false,
            'error'   => PremiumRequest::getLastError() ?? 'Failed to submit request',
        ];
    }
}



⸻

Example PremiumRequest Model Method

If you don’t already have a model or methods like isPendingRequest() or submitRequest(), here’s an example of how you might structure them:

<?php

namespace App\Models;

use App\Database;

class PremiumRequest
{
    public static function isPendingRequest(int $userId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM premium_requests WHERE user_id = ? AND status = 'pending'");
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return !empty($result);
    }

    public static function submitRequest(int $userId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO premium_requests (user_id, status) VALUES (?, 'pending')");
        $stmt->bind_param('i', $userId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}



⸻

Additional Considerations:
	1.	Premium Membership Criteria: You might want to add further validation for users who can apply for premium status. This could include checking their account age, balance, or specific achievements.
	2.	Approval Process: If there’s an approval process before granting premium status, you could add an admin check to approve or reject requests stored in premium_requests.
	3.	Notification: You may implement email or in-app notifications to inform the user about the status of their premium request.
	4.	Premium Features: Ensure that you have a mechanism to unlock premium features for the user after their request is approved.

⸻

Let me know if you’d like to tweak any specific behavior, add more validation, or further integrate this with your user management!
Here’s the refactored ForgetPasswordController, following the same service-based approach, handling user requests for password reset functionality.

⸻

Refactored ForgetPasswordController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Auth\ForgetPasswordService;

class ForgetPasswordController extends Controller
{
    protected ForgetPasswordService $forgetPasswordService;

    public function __construct(ForgetPasswordService $forgetPasswordService)
    {
        parent::__construct();
        $this->forgetPasswordService = $forgetPasswordService;
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        // Render the "forgot password" view where the user can enter their email
        $this->renderBuyerTemplate('auth/forget-password/index', [
            'page_title' => 'Forgot Password',
        ]);
    }

    public function submitRequest(): void
    {
        // Handle password reset request
        $response = $this->forgetPasswordService->submitPasswordResetRequest();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }

    public function resetPassword(): void
    {
        // Handle the actual password reset process after token validation
        $response = $this->forgetPasswordService->resetPassword();

        http_response_code($response['code']);
        unset($response['code']);

        echo json_encode($response);
    }
}



⸻

Example ForgetPasswordService.php (under App\Services\Auth)

<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\PasswordResetRequest;
use App\Session;
use App\Helpers\CsrfHelper;

class ForgetPasswordService
{
    public function submitPasswordResetRequest(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['email'])) {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Email is required.',
            ];
        }

        $email = $_POST['email'];

        // Check if the email exists in the database
        $user = User::findByEmail($email);
        if (!$user) {
            return [
                'code'    => 404,
                'success' => false,
                'error'   => 'User with this email does not exist.',
            ];
        }

        // Generate a password reset token and save it
        $token = bin2hex(random_bytes(16)); // Generate a secure token
        PasswordResetRequest::createRequest($user->id, $token);

        // Send password reset email (this could be implemented with a mailer service)
        $this->sendResetEmail($user->email, $token);

        return [
            'code'    => 200,
            'success' => true,
            'message' => 'Password reset link has been sent to your email.',
        ];
    }

    public function resetPassword(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['token']) || empty($_POST['password'])) {
            return [
                'code'    => 400,
                'success' => false,
                'error'   => 'Token and new password are required.',
            ];
        }

        $token = $_POST['token'];
        $newPassword = $_POST['password'];

        // Validate the token
        $resetRequest = PasswordResetRequest::findByToken($token);
        if (!$resetRequest) {
            return [
                'code'    => 404,
                'success' => false,
                'error'   => 'Invalid or expired token.',
            ];
        }

        // Update the user's password
        $user = User::findById($resetRequest->user_id);
        if (!$user) {
            return [
                'code'    => 404,
                'success' => false,
                'error'   => 'User not found.',
            ];
        }

        // Update password
        $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
        $user->save();

        // Delete the reset request token
        PasswordResetRequest::deleteByToken($token);

        return [
            'code'    => 200,
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ];
    }

    private function sendResetEmail(string $email, string $token): void
    {
        // Example: Implement your email sending logic here
        $resetLink = "https://yourwebsite.com/reset-password?token=" . $token;
        $subject = "Password Reset Request";
        $message = "To reset your password, click the following link: $resetLink";
        
        // Use a mailer or PHP mail() function to send the email
        mail($email, $subject, $message);
    }
}



⸻

Example PasswordResetRequest Model Methods

<?php

namespace App\Models;

use App\Database;

class PasswordResetRequest
{
    public static function createRequest(int $userId, string $token): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO password_reset_requests (user_id, token, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param('is', $userId, $token);

        return $stmt->execute();
    }

    public static function findByToken(string $token)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM password_reset_requests WHERE token = ? LIMIT 1");
        $stmt->bind_param('s', $token);
        $stmt->execute();

        return $stmt->get_result()->fetch_object();
    }

    public static function deleteByToken(string $token): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM password_reset_requests WHERE token = ?");
        $stmt->bind_param('s', $token);

        return $stmt->execute();
    }
}



⸻

Additional Considerations:
	1.	Email Configuration: Make sure to configure the email sending method properly (e.g., using PHPMailer or another service). The sendResetEmail() function in the service is where you’ll implement the email sending logic.
	2.	Token Expiry: You may want to implement an expiry for the reset token (e.g., 1 hour) to prevent the reset link from being used after a long period.
	3.	Security: Ensure that the password is hashed securely before saving it in the database (as shown using password_hash()).
	4.	User Feedback: The controller’s methods return JSON responses. You may want to enhance this to provide more user-friendly messages, especially in case of errors.

⸻

Let me know if you’d like further adjustments or additions to this!

Here’s a refactored FAQController to handle FAQ-related functionality, such as displaying a list of FAQs and possibly adding, updating, or deleting them.

⸻

Refactored FAQController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\FAQService;

class FAQController extends Controller
{
    protected FAQService $faqService;

    public function __construct(FAQService $faqService)
    {
        parent::__construct();
        $this->faqService = $faqService;
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        // Fetch all FAQs from the service
        $faqs = $this->faqService->getAllFAQs();

        // Render the FAQ page with the list of FAQs
        $this->renderBuyerTemplate('faq/index', [
            'page_title' => 'Frequently Asked Questions',
            'faqs'       => $faqs,
        ]);
    }

    public function show(int $id): void
    {
        // Fetch a specific FAQ by ID
        $faq = $this->faqService->getFAQById($id);

        if (!$faq) {
            // If FAQ not found, redirect or show error
            $this->redirect('faq');
            return;
        }

        // Render the specific FAQ page
        $this->renderBuyerTemplate('faq/show', [
            'page_title' => 'FAQ Details',
            'faq'        => $faq,
        ]);
    }

    public function create(): void
    {
        // Render the page to create a new FAQ
        $this->renderAdminTemplate('faq/create', [
            'page_title' => 'Create FAQ',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = $_POST['question'] ?? '';
            $answer = $_POST['answer'] ?? '';

            $response = $this->faqService->createFAQ($question, $answer);

            if ($response['success']) {
                // Redirect to FAQ list or success page
                $this->redirect('faq');
            } else {
                // Show error message
                $this->renderAdminTemplate('faq/create', [
                    'page_title' => 'Create FAQ',
                    'error'      => $response['error'],
                ]);
            }
        }
    }

    public function edit(int $id): void
    {
        // Fetch the FAQ by ID for editing
        $faq = $this->faqService->getFAQById($id);

        if (!$faq) {
            // If FAQ not found, redirect or show error
            $this->redirect('faq');
            return;
        }

        // Render the page to edit the FAQ
        $this->renderAdminTemplate('faq/edit', [
            'page_title' => 'Edit FAQ',
            'faq'        => $faq,
        ]);
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = $_POST['question'] ?? '';
            $answer = $_POST['answer'] ?? '';

            $response = $this->faqService->updateFAQ($id, $question, $answer);

            if ($response['success']) {
                // Redirect to FAQ list or success page
                $this->redirect('faq');
            } else {
                // Show error message
                $this->renderAdminTemplate('faq/edit', [
                    'page_title' => 'Edit FAQ',
                    'faq'        => $faq,
                    'error'      => $response['error'],
                ]);
            }
        }
    }

    public function destroy(int $id): void
    {
        // Delete the FAQ
        $response = $this->faqService->deleteFAQ($id);

        if ($response['success']) {
            // Redirect to FAQ list or success page
            $this->redirect('faq');
        } else {
            // Show error message
            $this->renderAdminTemplate('faq/index', [
                'page_title' => 'Frequently Asked Questions',
                'error'      => $response['error'],
            ]);
        }
    }
}



⸻

Example FAQService.php (under App\Services)

<?php

namespace App\Services;

use App\Models\FAQ;

class FAQService
{
    public function getAllFAQs(): array
    {
        // Fetch all FAQs from the database
        return FAQ::getAll();
    }

    public function getFAQById(int $id)
    {
        // Fetch a specific FAQ by ID
        return FAQ::findById($id);
    }

    public function createFAQ(string $question, string $answer): array
    {
        // Validate inputs
        if (empty($question) || empty($answer)) {
            return [
                'success' => false,
                'error'   => 'Both question and answer are required.',
            ];
        }

        // Create new FAQ
        if (FAQ::create($question, $answer)) {
            return [
                'success' => true,
                'message' => 'FAQ created successfully.',
            ];
        }

        return [
            'success' => false,
            'error'   => 'Failed to create FAQ.',
        ];
    }

    public function updateFAQ(int $id, string $question, string $answer): array
    {
        // Validate inputs
        if (empty($question) || empty($answer)) {
            return [
                'success' => false,
                'error'   => 'Both question and answer are required.',
            ];
        }

        // Update FAQ
        if (FAQ::update($id, $question, $answer)) {
            return [
                'success' => true,
                'message' => 'FAQ updated successfully.',
            ];
        }

        return [
            'success' => false,
            'error'   => 'Failed to update FAQ.',
        ];
    }

    public function deleteFAQ(int $id): array
    {
        // Delete FAQ
        if (FAQ::delete($id)) {
            return [
                'success' => true,
                'message' => 'FAQ deleted successfully.',
            ];
        }

        return [
            'success' => false,
            'error'   => 'Failed to delete FAQ.',
        ];
    }
}



⸻

Example FAQ Model Methods

<?php

namespace App\Models;

use App\Database;

class FAQ
{
    public static function getAll(): array
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM faqs ORDER BY created_at DESC");

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function findById(int $id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM faqs WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public static function create(string $question, string $answer): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO faqs (question, answer, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param('ss', $question, $answer);

        return $stmt->execute();
    }

    public static function update(int $id, string $question, string $answer): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE faqs SET question = ?, answer = ? WHERE id = ?");
        $stmt->bind_param('ssi', $question, $answer, $id);

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM faqs WHERE id = ?");
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }
}



⸻

Additional Considerations:
	1.	Validation: The service ensures that both the question and answer fields are provided when creating or updating FAQs.
	2.	Security: Sanitize user inputs before saving them to the database.
	3.	Authorization: Ensure that only authorized users (e.g., admins) can create, edit, or delete FAQs.
	4.	Pagination: If there are many FAQs, you might want to add pagination to the FAQ listing to improve performance.

⸻

Let me know if you want any specific enhancements or additional functionality!
Here’s the refactored LoginController. This controller will handle the login process for users, validate credentials, and establish user sessions.

⸻

Refactored LoginController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Helpers\CsrfHelper;
use Session;

class LoginController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        // Render the login page
        $this->renderBuyerTemplate('login/index', [
            'page_title' => 'Login',
            'csrf_token' => CsrfHelper::generateToken(),
        ]);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate CSRF token
            if (!CsrfHelper::isValidToken($_POST['csrf_token'])) {
                $this->redirect('login');
                return;
            }

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate input fields
            if (empty($username) || empty($password)) {
                $this->renderBuyerTemplate('login/index', [
                    'page_title' => 'Login',
                    'error' => 'Username and password are required.',
                    'csrf_token' => CsrfHelper::generateToken(),
                ]);
                return;
            }

            // Authenticate user
            $user = $this->authService->authenticate($username, $password);

            if ($user) {
                // User successfully logged in, set session
                Session::set('user_id', $user->id);
                Session::set('username', $user->username);
                Session::set('api_token', $this->authService->generateApiToken($user));

                // Redirect to dashboard or user area
                $this->redirect('dashboard');
            } else {
                // Invalid credentials, show error
                $this->renderBuyerTemplate('login/index', [
                    'page_title' => 'Login',
                    'error' => 'Invalid username or password.',
                    'csrf_token' => CsrfHelper::generateToken(),
                ]);
            }
        } else {
            $this->redirect('login');
        }
    }

    public function logout(): void
    {
        // Destroy user session
        Session::destroy();

        // Redirect to login page
        $this->redirect('login');
    }
}



⸻

AuthService.php (under App\Services)

This service class handles user authentication and token generation.

<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\HashHelper;

class AuthService
{
    public function authenticate(string $username, string $password): ?User
    {
        // Fetch user by username
        $user = User::findByUsername($username);

        // Check if user exists and password matches
        if ($user && HashHelper::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function generateApiToken(User $user): string
    {
        // Generate an API token for the authenticated user
        return bin2hex(random_bytes(32)); // Example of simple token generation
    }
}



⸻

User Model Methods

This model provides basic user data fetching functionality, such as retrieving a user by username.

<?php

namespace App\Models;

use App\Database;

class User
{
    public static function findByUsername(string $username): ?User
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            return new self($user);
        }

        return null;
    }

    public function __construct(array $userData)
    {
        $this->id = $userData['id'];
        $this->username = $userData['username'];
        $this->password = $userData['password']; // Ensure password is hashed
    }
}



⸻

CsrfHelper.php

Ensure the CSRF helper provides necessary token generation and validation functions.

<?php

namespace App\Helpers;

class CsrfHelper
{
    public static function generateToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function isValidToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }
}



⸻

login/index.php (View)

Here’s a basic view template for the login page:

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ page_title }}</title>
</head>
<body>
    <h1>{{ page_title }}</h1>

    {% if error %}
        <p style="color: red;">{{ error }}</p>
    {% endif %}

    <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
        
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Login</button>
    </form>
</body>
</html>



⸻

Session Management

Make sure you have a session management system in place. Here’s a simple session class if you don’t already have one:

<?php

namespace App\Helpers;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy(): void
    {
        session_destroy();
    }
}



⸻

Additional Considerations:
	•	Password Hashing: The AuthService uses HashHelper::check to check if the entered password matches the hashed password in the database. Ensure you’re using a secure password hashing mechanism like password_hash/password_verify or a custom hashing approach like HashHelper.
	•	Security: Make sure to implement security practices such as rate limiting login attempts and logging failed login attempts to prevent brute force attacks.
	•	Redirects: After successful login, you can redirect users to their dashboard or a landing page. Adjust the redirect('dashboard') accordingly.

⸻

This structure should help you build a solid, secure login process. Let me know if you need further modifications or additional features!

Here is the modified RegisterController to handle user registration. It validates the input fields, checks if the username already exists, hashes the password, and stores the new user in the database.

⸻

Refactored RegisterController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Helpers\CsrfHelper;
use App\Helpers\HashHelper;

class RegisterController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
        $this->template->setViewsPath(__DIR__ . '/../Views');
    }

    public function index(): void
    {
        // Render the registration page
        $this->renderBuyerTemplate('register/index', [
            'page_title' => 'Register',
            'csrf_token' => CsrfHelper::generateToken(),
        ]);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate CSRF token
            if (!CsrfHelper::isValidToken($_POST['csrf_token'])) {
                $this->redirect('register');
                return;
            }

            // Get the input data
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validate input fields
            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                $this->renderBuyerTemplate('register/index', [
                    'page_title' => 'Register',
                    'error' => 'All fields are required.',
                    'csrf_token' => CsrfHelper::generateToken(),
                ]);
                return;
            }

            // Check if passwords match
            if ($password !== $confirmPassword) {
                $this->renderBuyerTemplate('register/index', [
                    'page_title' => 'Register',
                    'error' => 'Passwords do not match.',
                    'csrf_token' => CsrfHelper::generateToken(),
                ]);
                return;
            }

            // Check if username or email already exists
            if (User::findByUsername($username)) {
                $this->renderBuyerTemplate('register/index', [
                    'page_title' => 'Register',
                    'error' => 'Username already exists.',
                    'csrf_token' => CsrfHelper::generateToken(),
                ]);
                return;
            }

            if (User::findByEmail($email)) {
                $this->renderBuyerTemplate('register/index', [
                    'page_title' => 'Register',
                    'error' => 'Email already exists.',
                    'csrf_token' => CsrfHelper::generateToken(),
                ]);
                return;
            }

            // Hash the password
            $hashedPassword = HashHelper::make($password);

            // Create new user
            $user = new User([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
            ]);

            // Save the user to the database
            if ($user->save()) {
                // User registration successful, log in the user and redirect
                $this->authService->authenticate($username, $password);
                $this->redirect('dashboard');
            } else {
                $this->renderBuyerTemplate('register/index', [
                    'page_title' => 'Register',
                    'error' => 'An error occurred while creating your account. Please try again.',
                    'csrf_token' => CsrfHelper::generateToken(),
                ]);
            }
        } else {
            $this->redirect('register');
        }
    }
}



⸻

AuthService.php (Update for User Registration)

Make sure the AuthService class includes logic for password hashing and authentication:

<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\HashHelper;

class AuthService
{
    public function authenticate(string $username, string $password): ?User
    {
        // Fetch user by username
        $user = User::findByUsername($username);

        // Check if user exists and password matches
        if ($user && HashHelper::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function generateApiToken(User $user): string
    {
        // Generate an API token for the authenticated user
        return bin2hex(random_bytes(32)); // Example of simple token generation
    }
}



⸻

User Model (with Registration Logic)

Make sure your User model provides necessary methods for saving a new user and checking for existing usernames and emails.

<?php

namespace App\Models;

use App\Database;

class User
{
    public static function findByUsername(string $username): ?User
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            return new self($user);
        }

        return null;
    }

    public static function findByEmail(string $email): ?User
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            return new self($user);
        }

        return null;
    }

    public function __construct(array $userData)
    {
        $this->id = $userData['id'];
        $this->username = $userData['username'];
        $this->email = $userData['email'];
        $this->password = $userData['password']; // Ensure password is hashed
    }

    public function save(): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $this->username, $this->email, $this->password);

        return $stmt->execute();
    }
}



⸻

HashHelper.php (Password Hashing)

Ensure the HashHelper is used for secure password hashing and checking:

<?php

namespace App\Helpers;

class HashHelper
{
    public static function make(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function check(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}



⸻

register/index.php (View Template)

Here’s a basic view for the registration page:

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ page_title }}</title>
</head>
<body>
    <h1>{{ page_title }}</h1>

    {% if error %}
        <p style="color: red;">{{ error }}</p>
    {% endif %}

    <form action="/register" method="POST">
        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
        
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>



⸻

Session Management

Ensure the session management for successful registration and login is properly handled, as discussed in the previous controllers.

⸻

Redirect Handling

Once the registration is successful, users will be redirected to their dashboard or landing page. Adjust the redirect('dashboard') to fit your application’s flow.

⸻

With this refactored RegisterController, the user will be able to register, and you ensure proper validation, hashing, and redirection.

Let me know if you need further adjustments!Here’s a clean and structured version of the CheckOutController, modeled in line with your controller architecture and service usage pattern:

⸻

CheckOutController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\CsrfHelper;
use App\Models\User;
use App\Services\Order\CheckoutService;

class CheckOutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        parent::__construct();
        $this->checkoutService = $checkoutService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Point to buyer view folder
    }

    public function index(): void
    {
        $user = $this->getUser();

        $checkoutData = $this->checkoutService->getCheckoutData($user);

        $data = array_merge($checkoutData, [
            'page_title' => 'Checkout',
            'user'       => $user,
            'csrf_token' => CsrfHelper::generateToken(),
        ]);

        $this->renderBuyerTemplate('checkout/index', $data);
    }

    public function process(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
            exit;
        }

        if (!CsrfHelper::isValidToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            exit;
        }

        $user = $this->getUser();
        $result = $this->checkoutService->handleCheckout($user, $_POST);

        if ($result['success']) {
            echo json_encode(['success' => true, 'message' => 'Checkout completed successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Checkout failed']);
        }
    }
}



⸻

Expected Methods in CheckoutService

Your CheckoutService should include:
	•	getCheckoutData(User $user): array
	•	handleCheckout(User $user, array $formData): array

⸻

Would you like help drafting the CheckoutService class or the associated view (checkout/index.php)?Here’s a clean and structured version of a CronController, intended for background/automated tasks like expiring orders, updating statuses, etc. It follows your system’s architecture pattern:

⸻

CronController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\System\CronService;

class CronController extends Controller
{
    protected CronService $cronService;

    public function __construct(CronService $cronService)
    {
        parent::__construct();
        $this->cronService = $cronService;
    }

    public function run(): void
    {
        // Run scheduled tasks (manually or via actual cron job)
        $results = $this->cronService->runScheduledJobs();

        // Output basic response (for logging or debugging)
        echo json_encode([
            'success' => true,
            'message' => 'Cron jobs executed',
            'results' => $results
        ]);
    }

    public function cleanup(): void
    {
        // Example cleanup task
        $status = $this->cronService->performCleanup();

        echo json_encode([
            'success' => $status,
            'message' => $status ? 'Cleanup completed' : 'Cleanup failed'
        ]);
    }
}



⸻

Expected Methods in CronService

Your CronService should include:

public function runScheduledJobs(): array;
public function performCleanup(): bool;



⸻

Example Usage in Crontab

To run the cron via CLI:

php /path/to/project/index.php cron/run

Or set a crontab like:

* * * * * /usr/bin/php /path/to/project/index.php cron/run >> /var/log/app_cron.log 2>&1



⸻

Let me know if you’d like help writing the CronService class or want to automate specific types of system tasks like order expiration or report reminders.Here’s how you can refactor TicketController to cleanly delegate business logic to a TicketService, following your preferred architecture.

⸻

Refactored TicketController.php

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\CsrfHelper;
use App\Models\User;
use App\Services\Support\TicketService;

class TicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        parent::__construct();
        $this->ticketService = $ticketService;
        $this->template->setViewsPath(__DIR__ . '/../Views'); // Buyer views path
    }

    public function index(): void
    {
        $user = $this->getUser();
        $data = $this->ticketService->getTicketOverviewData($user);

        $data['page_title'] = 'Support Tickets';
        $data['csrf_token'] = CsrfHelper::generateToken();
        $data['user'] = $user;

        $this->renderBuyerTemplate('tickets/index', $data);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
            return;
        }

        if (!CsrfHelper::isValidToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            return;
        }

        $user = $this->getUser();
        $response = $this->ticketService->createTicket($user, $_POST);

        if ($response['success']) {
            echo json_encode(['success' => true, 'message' => 'Ticket created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $response['error'] ?? 'Failed to create ticket']);
        }
    }

    public function view(int $ticketId): void
    {
        $user = $this->getUser();
        $ticketData = $this->ticketService->getTicketDetails($user, $ticketId);

        if (!$ticketData) {
            http_response_code(404);
            $this->renderBuyerTemplate('errors/404', ['page_title' => 'Ticket Not Found']);
            return;
        }

        $ticketData['page_title'] = 'View Ticket';
        $ticketData['user'] = $user;
        $ticketData['csrf_token'] = CsrfHelper::generateToken();

        $this->renderBuyerTemplate('tickets/view', $ticketData);
    }

    public function reply(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Invalid method']);
            return;
        }

        if (!CsrfHelper::isValidToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            return;
        }

        $user = $this->getUser();
        $response = $this->ticketService->replyToTicket($user, $_POST);

        if ($response['success']) {
            echo json_encode(['success' => true, 'message' => 'Reply posted']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $response['error'] ?? 'Failed to reply']);
        }
    }
}



⸻

Expected Methods in TicketService

Your TicketService should include:

public function getTicketOverviewData(User $user): array;
public function createTicket(User $user, array $data): array;
public function getTicketDetails(User $user, int $ticketId): ?array;
public function replyToTicket(User $user, array $data): array;



⸻

Let me know if you want help implementing the TicketService or creating views (tickets/index.php, tickets/view.php).