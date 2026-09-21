<?php
$db_host = 'sql213.infinityfree.com';
$db_user = 'if0_42953938';
$db_pass = '6uaLBecpgiKM';
$db_name = 'if0_42953938_School_DB';
$prefix  = 'demo';

session_name("ActiveBus_" . $prefix);
session_set_cookie_params([
    "path" => "/" . $prefix . "/",
    "httponly" => true,
    "samesite" => "Lax"
]);

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Database Connection Error");
}

// SUBSCRIPTION & LOCKOUT CHECK
$sub_check = $conn->prepare("SELECT status FROM district_subscriptions WHERE subdomain = ?");
$sub_check->bind_param("s", $prefix);
$sub_check->execute();
$sub_res = $sub_check->get_result()->fetch_assoc();
$is_locked = ($sub_res && $sub_res["status"] === "past_due");

$current_page = basename($_SERVER['PHP_SELF']);
if ($is_locked && $current_page !== "billing.php") {
    header("Location: billing.php");
    exit();
}

$settings_res = $conn->query("SELECT * FROM `{$prefix}_settings` WHERE id = 1");
$sys_settings = $settings_res ? $settings_res->fetch_assoc() : [];

$school = !empty($sys_settings["school_name"]) ? $sys_settings["school_name"] : "Demo District";
$school_logo = $sys_settings["logo_url"] ?? "";
$school_phone = $sys_settings["phone"] ?? "";
$school_email = $sys_settings["email"] ?? "";
$school_address = $sys_settings["address"] ?? "";

function render_header($page_name = "Dashboard", $breadcrumb = null, $show_logout = false) {
    global $school, $school_logo;
    $logo_html = '';
    if (!empty($school_logo)) {
        $logo_html = '<img src="' . htmlspecialchars($school_logo) . '" alt="' . htmlspecialchars($school) . ' Logo">';
    }
    
    $breadcrumb_html = '';
    if ($breadcrumb) {
        $breadcrumb_html = '<div class="breadcrumb">';
        $breadcrumb_html .= '<a href="main.php">🏠 Home</a>';
        foreach ($breadcrumb as $path => $label) {
            $breadcrumb_html .= '<span>/</span>';
            if (is_array($label)) {
                $breadcrumb_html .= '<a href="' . htmlspecialchars($label[1]) . '">' . htmlspecialchars($label[0]) . '</a>';
            } else {
                $breadcrumb_html .= '<span>' . htmlspecialchars($label) . '</span>';
            }
        }
        $breadcrumb_html .= '</div>';
    }

    $logout_html = '';
    if ($show_logout) {
        $logout_html = '<a href="?action=logout" class="logout-btn">↗️ Logout</a>';
    }

    echo <<<HTML
    <header class="header">
        <div class="header-left">
            <div class="header-logo">
                $logo_html
                <div>
                    <div class="header-title">$school</div>
                    <div class="header-subtitle">$page_name</div>
                </div>
            </div>
            $breadcrumb_html
        </div>
        <div class="header-actions">
            $logout_html
        </div>
    </header>
    HTML;
}

function render_footer() {
    global $school, $school_phone, $school_email, $school_address;
    
    $address_html = '';
    if (!empty($school_address)) {
        $address_html = '<p>📍 ' . htmlspecialchars($school_address) . '</p>';
    }
    
    $contact_html = '';
    if (!empty($school_phone) || !empty($school_email)) {
        $contact_html = '<p>';
        if (!empty($school_phone)) $contact_html .= '📞 ' . htmlspecialchars($school_phone) . ' ';
        if (!empty($school_email)) $contact_html .= '✉️ ' . htmlspecialchars($school_email);
        $contact_html .= '</p>';
    }

    echo <<<HTML
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>$school</h3>
                <p>Central Office</p>
            </div>
            <div class="footer-section">
                $address_html
                $contact_html
            </div>
            <div class="footer-section">
                <p style="font-size: 0.8rem; color: #999;">ActiveBus GPS Tracking System<br>© 2025 All rights reserved</p>
            </div>
        </div>
    </footer>
    HTML;
}
?>
