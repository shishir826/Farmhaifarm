let cart = JSON.parse(localStorage.getItem("farmfreshCart")) || [];

function saveCart() {
  localStorage.setItem("farmfreshCart", JSON.stringify(cart));
}

document.querySelectorAll(".product-card").forEach((card) => {
  const minusButton = card.querySelector('button[aria-label="decrease"]');
  const plusButton = card.querySelector('button[aria-label="increase"]');
  const quantityDisplay = card.querySelector(".qty-selector span");
  const addButton = card.querySelector(".add-cart-btn");

  if (!minusButton || !plusButton || !quantityDisplay || !addButton) return;

  let quantity = parseInt(quantityDisplay.textContent) || 1;

  plusButton.addEventListener("click", () => {
    const stock = parseInt(card.dataset.stock);

    if (quantity < stock) {
      quantity++;
      quantityDisplay.textContent = quantity;
    }
  });

  minusButton.addEventListener("click", () => {
    if (quantity > 1) {
      quantity--;
      quantityDisplay.textContent = quantity;
    }
  });

  addButton.addEventListener("click", () => {
    const productId = parseInt(card.dataset.productId);
    const productName = card.dataset.productName;
    const price = parseFloat(card.dataset.price);
    const stock = parseInt(card.dataset.stock);

    const existingProduct = cart.find((item) => item.productId === productId);

    if (existingProduct) {
      const newQuantity = existingProduct.quantity + quantity;

      if (newQuantity > stock) {
        alert("Not enough stock available.");
        return;
      }

      existingProduct.quantity = newQuantity;
    } else {
      cart.push({
        productId,
        productName,
        price,
        quantity,
      });
    }

    saveCart();

    addButton.textContent = "Added ✓";

    setTimeout(() => {
      addButton.textContent = "Add to Cart";
    }, 1200);

    console.log("Cart saved:", cart);
  });
});

function displayCart() {
  const cartItemsContainer = document.getElementById("cartItems");
  const cartTotal = document.getElementById("cartTotal");
  const cartItemCount = document.getElementById("cartItemCount");

  if (!cartItemsContainer) return;

  cartItemsContainer.innerHTML = "";

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `
      <div class="empty-cart">
        <h2>Your cart is empty</h2>
        <p>Add some fresh products from the shop.</p>
        <br>
        <a href="shop.html">Go to Shop</a>
      </div>
    `;

    cartTotal.textContent = "रु0";
    cartItemCount.textContent = "0";

    return;
  }

  let total = 0;
  let itemCount = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;

    total += itemTotal;
    itemCount += item.quantity;

    const cartItem = document.createElement("div");

    cartItem.className = "cart-item";

    cartItem.innerHTML = `
      <div class="cart-item-info">
        <h3>${item.productName}</h3>
        <p>रु${item.price} / kg</p>
      </div>

      <div class="cart-quantity">
        <button onclick="changeCartQuantity(${index}, -1)">−</button>
        <span>${item.quantity}</span>
        <button onclick="changeCartQuantity(${index}, 1)">+</button>
      </div>

      <div class="cart-item-price">
        रु${itemTotal}
      </div>

      <button
        class="remove-btn"
        onclick="removeFromCart(${index})">
        Remove
      </button>
    `;

    cartItemsContainer.appendChild(cartItem);
  });

  cartTotal.textContent = "रु" + total;
  cartItemCount.textContent = itemCount;
}

function changeCartQuantity(index, change) {
  const item = cart[index];

  if (!item) return;

  const newQuantity = item.quantity + change;

  if (newQuantity < 1) return;

  item.quantity = newQuantity;

  saveCart();
  displayCart();
}

function removeFromCart(index) {
  if (index < 0 || index >= cart.length) return;

  cart.splice(index, 1);

  saveCart();
  displayCart();
}

<<<<<<< HEAD
=======
// ======================================
// BUY NOW
// ======================================

const buyButton = document.getElementById("buyButton");

if (buyButton) {

    buyButton.addEventListener("click", async () => {

        // Check cart
        if (cart.length === 0) {

            alert("Your cart is empty.");

            return;
        }


        // Disable button while processing
        buyButton.disabled = true;

        buyButton.textContent = "Processing...";


        try {

            // Create form data
            const formData = new FormData();

            formData.append(
                "cart",
                JSON.stringify(cart)
            );


            // Send cart to PHP
            const response = await fetch(
                "php/place-order.php",
                {
                    method: "POST",
                    body: formData
                }
            );


            const result = await response.json();


            // ==================================
            // SUCCESS
            // ==================================

            if (result.success) {

                alert(
                    "Order placed successfully!\n" +
                    "Order ID: " +
                    result.order_id
                );


                // Clear localStorage cart
                localStorage.removeItem(
                    "farmfreshCart"
                );


                // Clear JavaScript cart
                cart = [];


                // Refresh cart display
                displayCart();


            } else {

                alert(
                    "Order failed: " +
                    result.message
                );
            }


        } catch (error) {

            console.error(
                "Order error:",
                error
            );


            alert(
                "Something went wrong while placing your order."
            );


        } finally {

            buyButton.disabled = false;

            buyButton.textContent = "Buy Now";
        }

    });
}


// ======================================
// BUY NOW
// ======================================

const buyButton = document.getElementById("buyButton");

if (buyButton) {

  buyButton.addEventListener("click", async () => {

    // Check if cart is empty
    if (cart.length === 0) {
      alert("Your cart is empty.");
      return;
    }

    // Disable button while processing
    buyButton.disabled = true;
    buyButton.textContent = "Processing...";

    try {

      // Create form data
      const formData = new FormData();

      // Send cart to PHP
      formData.append(
        "cart",
        JSON.stringify(cart)
      );

      // Send request to PHP
      const response = await fetch(
        "php/place-order.php",
        {
          method: "POST",
          body: formData
        }
      );

      // Get PHP response
      const result = await response.json();

      console.log("Server response:", result);

      // ==================================
      // SUCCESS
      // ==================================

      if (result.success) {

        alert(
          "Order placed successfully!\n" +
          "Order ID: " +
          result.order_id
        );

        // Remove cart from localStorage
        localStorage.removeItem("farmfreshCart");

        // Empty current cart
        cart = [];

        // Refresh cart display
        displayCart();

      } else {

        alert(
          "Order failed: " +
          result.message
        );

      }

    } catch (error) {

      console.error("Order error:", error);

      alert(
        "Something went wrong while placing the order."
      );

    } finally {

      buyButton.disabled = false;
      buyButton.textContent = "Buy Now";

    }

  });

}


// ======================================
// LOAD CART PAGE
// ======================================

>>>>>>> 582f3222d7cbdb43cee23eafa8947f379270bb53
displayCart();
