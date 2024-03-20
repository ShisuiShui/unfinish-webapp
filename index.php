<?php

require_once 'BankAccount.php';

// Initialize sender and receiver objects
$sender = new BankAccount('Sender', '12345');
$receiver = new BankAccount('Receiver', '54321');
$sender->set_balance(1000); // Set initial balance for sender
$receiver->set_balance(2000); // Set initial balance for receiver

class TransactionManager
{
    public static function sendReceipt($sender, $receiver, $transactionType, $amount)
    {
        $senderName = $sender->get_name();
        $receiverName = $receiver->get_name();

        // Decrease sender's balance
        $sender->withdraw($amount);

        // Increase receiver's balance
        if ($transactionType === 'transfer') {
            // Adjust amount for transfer transaction
            $adjustedAmount = $amount;
            $receiver->deposit($adjustedAmount);
        } else {
            // For other transaction types, use the original amount
            $receiver->deposit($amount);
        }

        // Retrieve updated balances
        $senderBalance = $sender->get_balance();
        $receiverBalance = $receiver->get_balance();

        // Email subject
        $subject = 'Transaction Receipt';

        // Email body
        $message = "Dear $senderName,\n\n";
        $message .= "This is to confirm that a $transactionType of $amount has been made from your account to $receiverName's account.\n\n";
        $message .= "Your current balance: " . $senderBalance . "\n";
        $message .= "$receiverName's current balance: " . $receiverBalance . "\n\n";
        $message .= "Thank you for banking with us.\n\n";
        $message .= "Regards,\nYour Bank";

        // Display confirmation message
        echo "Receipt sent successfully to $senderName.\n";


        // Return updated balances
        return [$senderBalance, $receiverBalance];
    }
}


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Deposit from sender
    if (isset($_POST['deposit_sender'])) {
        $amount = $_POST['amount'];
        if ($sender->get_balance() >= $amount) { // Check if sender has sufficient balance
            list($senderBalance, $receiverBalance) = TransactionManager::sendReceipt($sender, $receiver, 'transfer', $amount);
            $sender->set_balance($senderBalance); // Update sender's balance
            $receiver->set_balance($receiverBalance); // Update receiver's balance
        } else {
            echo "Transaction failed due to insufficient balance in sender's account.\n";
        }
    }

    // Deposit from receiver
    if (isset($_POST['deposit_receiver'])) {
        $amount = $_POST['amount'];
        if ($receiver->get_balance() >= $amount) { // Check if receiver has sufficient balance
            list($receiverBalance, $senderBalance) = TransactionManager::sendReceipt($receiver, $sender, 'transfer', $amount);
            $sender->set_balance($senderBalance); // Update sender's balance
            $receiver->set_balance($receiverBalance); // Update receiver's balance
        } else {
            echo "Transaction failed due to insufficient balance in receiver's account.\n";
        }
    }
}

// Calculate total amount sent from Sender 1
$total_sent_sender1 = $sender->get_balance() - 1000;

// Calculate total amount sent from Sender 2
$total_sent_sender2 = $receiver->get_balance() - 2000;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Transaction Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .transaction-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #ccc;
        }

        .transaction-section h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .transaction-section p {
            font-size: 18px;
        }
    </style>
</head>

<body>
<div class="container">
    <h1 class="text-center mb-4">Bank Transaction Form</h1>
    <div class="row">
        <div class="col-md-6">
            <h2>Sender</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="amount_sender" class="form-label">Enter Amount:</label>
                    <input type="number" id="amount_sender" name="amount" class="form-control" placeholder="Enter amount..." required>
                </div>
                <div class="mb-3">
                    <label for="sender_balance" class="form-label">Sender's Balance:</label>
                    <input type="text" id="sender_balance" class="form-control" value="<?php echo $sender->get_balance(); ?>" readonly>
                </div>
                <button type="submit" name="deposit_sender" class="btn btn-primary">Send Money</button>
            </form>
        </div>
        <div class="col-md-6">
            <h2>Receiver</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                </div>
                <div class="mb-3">
                    <label for="receiver_balance" class="form-label">Receiver's Balance:</label>
                    <input type="text" id="receiver_balance" class="form-control" value="<?php echo $receiver->get_balance(); ?>" readonly>
                </div>
            </form>
        </div>
    </div>

    <!-- Transaction Section -->
    <div class="transaction-section">
        <div class="row">
            <div class="col-md-6">
                <h2>Money Sent</h2>
                <p>Total amount sent from Sender: <?php echo $total_sent_sender1; ?></p>
            </div>
            <div class="col-md-6">
                <h2>Money Received</h2>
                <p>Total amount received by Receiver: <?php echo $total_sent_sender2; ?></p>
            </div>
        </div>
    </div>
</div>
</body>

</html>
