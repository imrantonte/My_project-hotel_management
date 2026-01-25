<?php
// ================= DATABASE =================
$conn = new mysqli("localhost", "root", "", "registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ================= ROOM PRICES =================
$room_prices = [
    "Single" => 50,
    "Double" => 80,
    "Deluxe" => 120,
    "Suite"  => 200
];

$success = $error = "";

// ================= FORM SUBMIT =================
if (isset($_POST['book'])) {

    $name      = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $room_type = $_POST['room_type'];
    $checkin   = $_POST['checkin'];
    $checkout  = $_POST['checkout'];

    $payment_method = $_POST['payment_method'] ?? '';
    $payment_number = $_POST['payment_number'] ?? 'CARD';
    $total_price    = $_POST['total_price'] ?? 0;

    // 🔐 Check registered email
    $stmt = $conn->prepare("SELECT email FROM register WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "❌ This email is not registered. Please register first.";
    } else {

        // 💾 Insert booking
        $insert = $conn->prepare(
            "INSERT INTO bookings 
            (name, email, room_type, checkin, checkout, total_price, payment_method, payment_number)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $insert->bind_param(
            "sssssdss",
            $name,
            $email,
            $room_type,
            $checkin,
            $checkout,
            $total_price,
            $payment_method,
            $payment_number
        );

        if ($insert->execute()) {
            header("Location: booking.php?success=1");
            exit;
        } else {
            $error = "❌ Booking failed. Please try again.";
        }
    }
}

if (isset($_GET['success'])) {
    $success = "✅ Booking successful! Your payment has been recorded.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Luxury Booking | Grand Horizon</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
* { box-sizing: border-box; font-family: "Poppins", sans-serif; }
body {
    margin: 0;
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.booking-card {
    background: #fff;
    max-width: 520px;
    width: 100%;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
}
h1 { text-align: center; color:#203a43; }
.subtitle { text-align:center; color:#777; margin-bottom:20px; }
label { font-weight:600; margin-top:15px; display:block; }
input, select {
    width:100%; padding:12px; margin-top:6px;
    border-radius:10px; border:1px solid #ccc;
}
.price-box {
    background:#f4f7fa; border-radius:10px;
    padding:12px; margin-top:15px; font-weight:600;
}
button {
    margin-top:25px; width:100%; padding:15px;
    border:none; border-radius:50px;
    font-size:18px; font-weight:bold;
    background:linear-gradient(135deg,gold,#ffd700);
    cursor:pointer;
}
.msg { padding:12px; border-radius:10px; margin-bottom:15px; text-align:center; }
.success { background:#d4edda; color:#155724; }
.error { background:#f8d7da; color:#721c24; }
</style>
</head>

<body>

<div class="booking-card">
<h1>Book Your Luxury Stay</h1>
<p class="subtitle">Elegant rooms · Trusted payments</p>

<?php if($success) echo "<div class='msg success'>$success</div>"; ?>
<?php if($error) echo "<div class='msg error'>$error</div>"; ?>

<form method="POST">

<label>Full Name</label>
<input type="text" name="name" required>

<label>Email (Registered)</label>
<input type="email" name="email" required>

<label>Room Type</label>
<select name="room_type" id="room" required>
<option value="">Select Room</option>
<option value="Single" data-price="50">Single ($50/day)</option>
<option value="Double" data-price="80">Double ($80/day)</option>
<option value="Deluxe" data-price="120">Deluxe ($120/day)</option>
<option value="Suite" data-price="200">Suite ($200/day)</option>
</select>

<div class="price-box" id="roomPrice">Room Price: $0 / day</div>

<label>Check-in</label>
<input type="date" name="checkin" id="checkin" required>

<label>Check-out</label>
<input type="date" name="checkout" id="checkout" required>

<div class="price-box" id="totalPrice">Total Price: $0</div>


<input type="hidden" name="total_price" id="total_price">

<label>Payment Method</label>
<select name="payment_method" id="paymentMethod" required onchange="showPaymentFields()">
<option value="">Select</option>
<option value="bkash">bKash</option>
<option value="nagad">Nagad</option>
<option value="rocket">Rocket</option>
<option value="card">Card</option>
</select>

<div id="mobilePayment" style="display:none;">
<label>Mobile Number</label>
<input type="text" name="payment_number" placeholder="01XXXXXXXXX">
</div>

<div id="cardPayment" style="display:none;">
<input type="hidden" name="payment_number" value="CARD">
</div>

<button type="submit" name="book">Confirm Booking</button>

</form>
</div>

<script>
const room = document.getElementById("room");
const checkin = document.getElementById("checkin");
const checkout = document.getElementById("checkout");
const roomPrice = document.getElementById("roomPrice");
const totalPrice = document.getElementById("totalPrice");
const hiddenTotal = document.getElementById("total_price");

function calculateTotal(){
    const price = room.selectedOptions[0]?.dataset.price || 0;
    roomPrice.innerText = `Room Price: $${price} / day`;

    if(checkin.value && checkout.value){
        const d1 = new Date(checkin.value);
        const d2 = new Date(checkout.value);
        const days = Math.max(1,(d2-d1)/(1000*60*60*24));
        const total = days * price;
        totalPrice.innerText = `Total Price: $${total}`;
        hiddenTotal.value = total;
    }
}

room.onchange = checkin.onchange = checkout.onchange = calculateTotal;

function showPaymentFields(){
    mobilePayment.style.display = "none";
    cardPayment.style.display = "none";
    if(["bkash","nagad","rocket"].includes(paymentMethod.value)) mobilePayment.style.display="block";
    if(paymentMethod.value==="card") cardPayment.style.display="block";
}
</script>

</body>
</html>
