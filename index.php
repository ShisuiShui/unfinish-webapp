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

            // Send email (You would need to implement this part based on your email sending mechanism)
            // Example: mail($to, $subject, $message);
            // Note: This is just a dummy example, you need to implement your own email sending logic

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
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bank Transaction Form</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="stylesheet.php">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                padding: 20px;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                border-radius: 8px;
                padding: 30px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h1, h2 {
                text-align: center;
            }

            form {
                margin-top: 30px;
            }

            .form-label {
                font-weight: bold;
            }

            .form-control {
                margin-bottom: 15px;
            }

            .btn {
                width: 100%;
            }

            .balance-info {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>Bank Transaction Form</h1>
        <div class="row">
            <div class="col-md-6">
                <h2>Sender 1</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="amount_sender" class="form-label">Amount:</label>
                        <input type="number" id="amount_sender" name="amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="sender_balance" class="form-label">Balance:</label>
                        <input type="text" id="sender_balance" class="form-control" value="<?php echo $sender->get_balance(); ?>" readonly>
                    </div>
                    <button type="submit" name="deposit_sender" class="btn btn-primary">Send Money</button>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Sender 2</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="amount_receiver" class="form-label">Amount:</label>
                        <input type="number" id="amount_receiver" name="amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="receiver_balance" class="form-label">Balance:</label>
                        <input type="text" id="receiver_balance" class="form-control" value="<?php echo $receiver->get_balance(); ?>" readonly>
                    </div>
                    <button type="submit" name="deposit_receiver" class="btn btn-primary">Send Money</button>
                </form>
            </div>
        </div>
    </div>

