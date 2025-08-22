let selectedFood = null;
let userLoggedIn = true; // Replace this with real login/session check later

document.addEventListener("DOMContentLoaded", () => {
    // Load food items from backend
    fetch("public/foods_list.php")
        .then((res) => res.json())
        .then((foods) => {
            const foodList = document.getElementById("food-list");
            foodList.innerHTML = "";
            foods.forEach((food) => {
                const item = document.createElement("div");
                item.className = "food-item";
                item.innerHTML = `
      <img src="${food.image}" alt="${food.name}" class="food-image" />
      <h3>${food.name}</h3>
      <p>${food.description}</p>
      <p>₹${food.price}</p>
      <label>Qty: <input type="number" min="1" value="1" id="qty-${food.id}"></label>
      <button onclick="addToCart(${food.id}, '${food.name}', ${food.price})">Add to Order</button>
    `;
                foodList.appendChild(item);
            });

        });

    // Handle order form submission
    document.getElementById("order-form").addEventListener("submit", async function(e) {
        e.preventDefault();
        if (cart.length === 0) return alert("Please add items to your order.");

        const address = document.getElementById("address").value;
        const location = document.getElementById("location").value;
        const subscription = document.getElementById("subscription").value;

        // Example of applying discount
        let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        let discount = 0;
        if (subscription === "weekly") discount = total * 0.05;
        if (subscription === "monthly") discount = total * 0.10;

        const finalAmount = total - discount;

        // Step 1: Call payment API (if enabled)
        // Step 2: Call order.php with details
        const response = await fetch("public/order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                address,
                location,
                cart,
                subscription,
                total_price: finalAmount,
            }),
        });

        const result = await response.json();
        alert(result.message);
    });

    if (userLoggedIn) {
        document.getElementById("order-section").style.display = "block";
        document.getElementById("map-section").style.display = "block";
    }
});

// Select a food item
function selectFood(id, name, price) {
    selectedFood = { id, name, price };
    alert(`Selected: ${name}`);
}

// Google Maps – Mock live location
function initMap() {
    const deliveryLocation = { lat: 28.6139, lng: 77.2090 }; // Change to dynamic location
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: deliveryLocation,
    });

    const marker = new google.maps.Marker({
        position: deliveryLocation,
        map: map,
        title: "Your food is here!",
    });
}
let cart = [];

function addToCart(id, name, price) {
    const qty = parseInt(document.getElementById(`qty-${id}`).value || 1);
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.quantity += qty;
    } else {
        cart.push({ id, name, price, quantity: qty });
    }
    updateOrderSummary();
}

function updateOrderSummary() {
    const section = document.getElementById("order-summary");
    section.innerHTML = "<h3>Order Summary:</h3>";
    let total = 0;
    cart.forEach(item => {
        section.innerHTML += `<p>${item.name} x ${item.quantity} = ₹${item.quantity * item.price}</p>`;
        total += item.quantity * item.price;
    });
    section.innerHTML += `<strong>Total: ₹${total}</strong>`;
}

function startPayment() {
    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const subscription = document.getElementById("subscription").value;
    let discount = 0;
    if (subscription === "weekly") discount = total * 0.05;
    if (subscription === "monthly") discount = total * 0.10;
    const finalAmount = total - discount;

    alert(`Pretend paying ₹${finalAmount.toFixed(2)}... Payment successful!`);
    // You can trigger the order form submit here too
}