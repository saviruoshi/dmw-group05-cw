<?php
 include 'db.php';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    
    // Sanitize and validate input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        
        if ($stmt->execute()) {
            $success = "Message sent successfully! We'll get back to you soon.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

// Handle view messages request
$viewMessages = false;
if (isset($_GET['view']) && $_GET['view'] == 'messages') {
    $viewMessages = true;
    
    // Fetch all messages
    $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
    $result = $conn->query($sql);
}

$conn->close();
?>
<?php if (!$viewMessages): ?>
    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <h2>Contact Us</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="contact.php">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" />

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    <?php endif; ?>