<?php 
require_once "config.php";

// Check subscription status
$sub_check = $conn->prepare("SELECT status FROM district_subscriptions WHERE subdomain = ?");
$sub_check->bind_param("s", $prefix);
$sub_check->execute();
$sub_res = $sub_check->get_result()->fetch_assoc();
$is_locked = ($sub_res && $sub_res["status"] === "past_due");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $is_locked ? "Payment Required" : "Billing"; ?> | <?php echo htmlspecialchars($school); ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: <?php echo $is_locked ? "linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)" : "linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%)"; ?>;
    }

    .lockout-container {
      max-width: 600px;
      width: 90%;
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
      text-align: center;
    }

    .lockout-icon {
      font-size: 5rem;
      margin-bottom: 20px;
    }

    .lockout-title {
      font-size: 2rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 12px;
    }

    .lockout-message {
      font-size: 1.1rem;
      color: #64748b;
      margin-bottom: 32px;
      line-height: 1.6;
    }

    .alert-box {
      background: #fef2f2;
      border: 2px solid #fecaca;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 32px;
      text-align: left;
    }

    .alert-box h3 {
      color: #7f1d1d;
      margin-top: 0;
      font-size: 1rem;
    }

    .alert-box p {
      color: #991b1b;
      margin: 8px 0;
      font-size: 0.95rem;
    }

    .details-box {
      background: #f1f5f9;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 32px;
      text-align: left;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #e2e8f0;
    }

    .detail-item:last-child {
      border-bottom: none;
    }

    .detail-label {
      color: #64748b;
      font-weight: 500;
    }

    .detail-value {
      color: #1e293b;
      font-weight: 600;
    }

    .contact-info {
      background: #f1f5f9;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 32px;
      text-align: center;
    }

    .contact-info p {
      margin: 8px 0;
      color: #64748b;
    }

    .contact-info a {
      color: #2563eb;
      text-decoration: none;
      font-weight: 600;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    .payment-button {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      padding: 18px 36px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      width: 100%;
      margin-bottom: 16px;
      transition: transform 0.2s;
    }

    .payment-button:hover {
      transform: scale(1.02);
    }

    .support-button {
      background: white;
      color: #2563eb;
      border: 2px solid #2563eb;
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      width: 100%;
    }

    .support-button:hover {
      background: #f0f7ff;
    }

    .footer-text {
      color: #94a3b8;
      font-size: 0.85rem;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="lockout-container">
    <?php if ($is_locked): ?>
      <!-- PAYMENT REQUIRED LOCKOUT SCREEN -->
      <div class="lockout-icon">🔒</div>
      <h1 class="lockout-title">Payment Required</h1>
      <p class="lockout-message">
        Your ActiveBus subscription is past due. Please update your payment to restore access to your fleet management system.
      </p>

      <div class="alert-box">
        <h3>⚠️ Access Restricted</h3>
        <p>Your account has been locked due to an unpaid invoice. All users in your district will see this page until payment is received.</p>
      </div>

      <div class="details-box">
        <div class="detail-item">
          <span class="detail-label">District:</span>
          <span class="detail-value"><?php echo htmlspecialchars($school); ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Status:</span>
          <span class="detail-value" style="color: #ef4444;">🔴 Past Due</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Monthly Cost:</span>
          <span class="detail-value">$100.00</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Action Required:</span>
          <span class="detail-value">Update Payment Method</span>
        </div>
      </div>

      <button class="payment-button" onclick="alert('Redirect to payment portal')">💳 Update Payment Now</button>

      <div class="contact-info">
        <p><strong>Questions about this charge?</strong></p>
        <p>
          Contact our billing team at<br>
          📧 <a href="mailto:billing@activebus.com">billing@activebus.com</a><br>
          📞 1-800-BUS-TRACK
        </p>
      </div>

      <button class="support-button" onclick="alert('Support contact')">📞 Contact Support</button>

      <p class="footer-text">
        Once payment is received, access will be restored immediately.<br>
        We accept all major credit cards and electronic transfers.
      </p>

    <?php else: ?>
      <!-- NORMAL BILLING PAGE (IF ACCESSED DIRECTLY) -->
      <div class="lockout-icon">✅</div>
      <h1 class="lockout-title">Account in Good Standing</h1>
      <p class="lockout-message">
        Your subscription is active and paid. You have full access to ActiveBus.
      </p>

      <div class="details-box">
        <div class="detail-item">
          <span class="detail-label">District:</span>
          <span class="detail-value"><?php echo htmlspecialchars($school); ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Status:</span>
          <span class="detail-value" style="color: #10b981;">✅ Active</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Plan:</span>
          <span class="detail-value">Professional ($100/month)</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Next Billing:</span>
          <span class="detail-value">December 1, 2024</span>
        </div>
      </div>

      <button class="payment-button" onclick="history.back()" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);">← Return to Dashboard</button>

      <button class="support-button" onclick="alert('Manage account')">⚙️ Manage Account</button>

      <p class="footer-text">
        Thank you for choosing ActiveBus for your fleet management needs.
      </p>

    <?php endif; ?>
  </div>
</body>
</html>
