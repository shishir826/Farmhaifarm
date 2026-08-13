// ==========================================
// FARMFRESH CART
// ==========================================


// ==========================================
// LOAD CART FROM LOCAL STORAGE
// ==========================================

let cart = [];

try {
    cart = JSON.parse(
        localStorage.getItem("farmfreshCart")
    ) || [];
} catch (error) {
    console.error("Could not read cart:", error);
    cart = [];
}


// ==========================================
// SAVE CART
// ==========================================

function saveCart() {

    localStorage.setItem(
        "farmfreshCart",
        JSON.stringify(cart)
    );

}


// ==========================================
// SHOP PAGE
// ==========================================

function setupShop() {

    const productCards =
        document.querySelectorAll(".product-card");


    if (productCards.length === 0) {
        return;
    }


    productCards.forEach(function (card) {

        const minus =
            card.querySelector(
                'button[aria-label="decrease"]'
            );

        const plus =
            card.querySelector(
                'button[aria-label="increase"]'
            );

        const quantityDisplay =
            card.querySelector(
                ".qty-selector span"
            );

        const addButton =
            card.querySelector(
                ".add-cart-btn"
            );


        if (
            !minus ||
            !plus ||
            !quantityDisplay ||
            !addButton
        ) {
            return;
        }


        // ======================================
        // INITIAL QUANTITY
        // ======================================

        let quantity =
            parseInt(
                quantityDisplay.textContent
            ) || 1;


        // ======================================
        // PLUS
        // ======================================

        plus.addEventListener(
            "click",
            function () {

                const stock =
                    parseInt(
                        card.dataset.stock
                    ) || 0;


                if (quantity < stock) {

                    quantity++;

                    quantityDisplay.textContent =
                        quantity;

                }

            }
        );


        // ======================================
        // MINUS
        // ======================================

        minus.addEventListener(
            "click",
            function () {

                if (quantity > 1) {

                    quantity--;

                    quantityDisplay.textContent =
                        quantity;

                }

            }
        );


        // ======================================
        // ADD TO CART
        // ======================================

        addButton.addEventListener(
            "click",
            function () {

                const productId =
                    parseInt(
                        card.dataset.productId
                    );

                const productName =
                    card.dataset.productName;

                const price =
                    parseFloat(
                        card.dataset.price
                    );

                const stock =
                    parseInt(
                        card.dataset.stock
                    ) || 0;


                if (
                    !productId ||
                    !productName ||
                    isNaN(price)
                ) {

                    console.error(
                        "Invalid product data:",
                        card.dataset
                    );

                    return;
                }


                if (quantity > stock) {

                    alert(
                        "Not enough stock available."
                    );

                    return;
                }


                // Find existing item
                const existingItem =
                    cart.find(function (item) {

                        return (
                            Number(item.productId) ===
                            productId
                        );

                    });


                if (existingItem) {

                    const newQuantity =
                        Number(
                            existingItem.quantity
                        ) + quantity;


                    if (newQuantity > stock) {

                        alert(
                            "Not enough stock available."
                        );

                        return;
                    }


                    existingItem.quantity =
                        newQuantity;

                } else {

                    cart.push({

                        productId:
                            productId,

                        productName:
                            productName,

                        price:
                            price,

                        quantity:
                            quantity

                    });

                }


                // Save
                saveCart();


                console.log(
                    "Cart saved:",
                    cart
                );


                // Button feedback
                addButton.textContent =
                    "Added ✓";


                setTimeout(function () {

                    addButton.textContent =
                        "Add to Cart";

                }, 1200);

            }
        );

    });

}


// ==========================================
// DISPLAY CART
// ==========================================

function displayCart() {

    const cartItems =
        document.getElementById(
            "cartItems"
        );


    // Not cart.html
    if (!cartItems) {
        return;
    }


    const cartTotal =
        document.getElementById(
            "cartTotal"
        );


    const cartItemCount =
        document.getElementById(
            "cartItemCount"
        );


    // Clear old content
    cartItems.innerHTML = "";


    // ======================================
    // EMPTY CART
    // ======================================

    if (cart.length === 0) {

        cartItems.innerHTML = `

            <div class="empty-cart">

                <h2>Your cart is empty</h2>

                <p>
                    Add some fresh products
                    from the shop.
                </p>

                <br>

                <a href="shop.html">
                    Go to Shop
                </a>

            </div>

        `;


        if (cartTotal) {
            cartTotal.textContent = "रु0";
        }


        if (cartItemCount) {
            cartItemCount.textContent = "0";
        }


        return;
    }


    // ======================================
    // CALCULATE TOTAL
    // ======================================

    let total = 0;

    let itemCount = 0;


    // ======================================
    // CREATE ITEMS
    // ======================================

    cart.forEach(
        function (item, index) {

            const price =
                Number(item.price) || 0;

            const quantity =
                Number(item.quantity) || 0;

            const itemTotal =
                price * quantity;


            total += itemTotal;

            itemCount += quantity;


            const itemElement =
                document.createElement(
                    "div"
                );


            itemElement.className =
                "cart-item";


            itemElement.innerHTML = `

                <div class="cart-item-info">

                    <h3>
                        ${item.productName}
                    </h3>

                    <p>
                        रु${price} / kg
                    </p>

                </div>


                <div class="cart-quantity">

                    <button
                        type="button"
                        class="cart-minus"
                        data-index="${index}"
                    >
                        −
                    </button>


                    <span>
                        ${quantity}
                    </span>


                    <button
                        type="button"
                        class="cart-plus"
                        data-index="${index}"
                    >
                        +
                    </button>

                </div>


                <div class="cart-item-price">

                    रु${itemTotal}

                </div>


                <button
                    type="button"
                    class="remove-btn"
                    data-index="${index}"
                >
                    Remove
                </button>

            `;


            cartItems.appendChild(
                itemElement
            );

        }
    );


    // ======================================
    // UPDATE TOTAL
    // ======================================

    if (cartTotal) {

        cartTotal.textContent =
            "रु" + total;

    }


    if (cartItemCount) {

        cartItemCount.textContent =
            itemCount;

    }


    // ======================================
    // CART MINUS
    // ======================================

    document
        .querySelectorAll(".cart-minus")
        .forEach(function (button) {

            button.addEventListener(
                "click",
                function () {

                    const index =
                        Number(
                            button.dataset.index
                        );


                    if (
                        cart[index].quantity > 1
                    ) {

                        cart[index].quantity--;

                    } else {

                        cart.splice(
                            index,
                            1
                        );

                    }


                    saveCart();

                    displayCart();

                }
            );

        });


    // ======================================
    // CART PLUS
    // ======================================

    document
        .querySelectorAll(".cart-plus")
        .forEach(function (button) {

            button.addEventListener(
                "click",
                function () {

                    const index =
                        Number(
                            button.dataset.index
                        );


                    cart[index].quantity++;


                    saveCart();

                    displayCart();

                }
            );

        });


    // ======================================
    // REMOVE
    // ======================================

    document
        .querySelectorAll(".remove-btn")
        .forEach(function (button) {

            button.addEventListener(
                "click",
                function () {

                    const index =
                        Number(
                            button.dataset.index
                        );


                    cart.splice(
                        index,
                        1
                    );


                    saveCart();

                    displayCart();

                }
            );

        });

}


// ==========================================
// BUY NOW
// ==========================================

function setupBuyButton() {

    const buyButton =
        document.getElementById(
            "buyButton"
        );


    if (!buyButton) {
        return;
    }


    buyButton.addEventListener(
        "click",
        async function () {

            if (cart.length === 0) {

                alert(
                    "Your cart is empty."
                );

                return;
            }


            buyButton.disabled = true;

            buyButton.textContent =
                "Processing...";


            try {

                const formData =
                    new FormData();


                formData.append(
                    "cart",
                    JSON.stringify(cart)
                );


                const response =
                    await fetch(
                        "php/place-order.php",
                        {
                            method: "POST",
                            body: formData
                        }
                    );


                const text =
                    await response.text();


                console.log(
                    "PHP RESPONSE:",
                    text
                );


                const result =
                    JSON.parse(text);


                if (result.success) {

                    alert(
                        "Order placed successfully!\n" +
                        "Order ID: " +
                        result.order_id
                    );


                    cart = [];


                    localStorage.removeItem(
                        "farmfreshCart"
                    );


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
                    "Could not place the order. " +
                    "Check the browser console."
                );

            } finally {

                buyButton.disabled = false;

                buyButton.textContent =
                    "Buy Now";

            }

        }
    );

}


// ==========================================
// START AFTER HTML HAS LOADED
// ==========================================

document.addEventListener(
    "DOMContentLoaded",
    function () {

        setupShop();

        displayCart();

        setupBuyButton();

        console.log(
            "FarmFresh cart loaded:",
            cart
        );

    }
);