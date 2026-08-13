// ==========================================
// FARMFRESH CART.JS
// ==========================================


// ==========================================
// CART STORAGE
// ==========================================

let cart = JSON.parse(
    localStorage.getItem("farmfreshCart")
) || [];


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

function initializeShop() {

    const productCards =
        document.querySelectorAll(".product-card");


    productCards.forEach((card) => {

        const minusButton =
            card.querySelector(
                'button[aria-label="decrease"]'
            );

        const plusButton =
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


        // Ignore incomplete/out-of-stock cards
        if (
            !minusButton ||
            !plusButton ||
            !quantityDisplay ||
            !addButton ||
            addButton.disabled
        ) {
            return;
        }


        let quantity =
            parseInt(quantityDisplay.textContent) || 1;


        // ======================================
        // PLUS
        // ======================================

        plusButton.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                const stock =
                    parseInt(
                        card.dataset.stock
                    ) || 0;


                if (stock > 0 && quantity < stock) {

                    quantity++;

                    quantityDisplay.textContent =
                        quantity;

                } else {

                    alert(
                        "You cannot add more than the available stock."
                    );
                }
            }
        );


        // ======================================
        // MINUS
        // ======================================

        minusButton.addEventListener(
            "click",
            function (event) {

                event.preventDefault();


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
            function (event) {

                event.preventDefault();


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


                // Check product information
                if (
                    !productId ||
                    !productName ||
                    isNaN(price)
                ) {

                    console.error(
                        "Invalid product information:",
                        card.dataset
                    );

                    alert(
                        "Unable to add this product."
                    );

                    return;
                }


                // Check stock
                if (stock <= 0) {

                    alert(
                        "This product is out of stock."
                    );

                    return;
                }


                // ==================================
                // CHECK EXISTING PRODUCT
                // ==================================

                const existingProduct =
                    cart.find(
                        function (item) {

                            return (
                                item.productId ===
                                productId
                            );
                        }
                    );


                if (existingProduct) {

                    const newQuantity =
                        existingProduct.quantity +
                        quantity;


                    if (newQuantity > stock) {

                        alert(
                            "Not enough stock available."
                        );

                        return;
                    }


                    existingProduct.quantity =
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


                // Save cart
                saveCart();


                // ==================================
                // BUTTON FEEDBACK
                // ==================================

                const originalText =
                    addButton.textContent;


                addButton.textContent =
                    "Added ✓";


                setTimeout(
                    function () {

                        addButton.textContent =
                            originalText;

                    },
                    1200
                );


                console.log(
                    "Cart:",
                    cart
                );
            }
        );
    });
}


// ==========================================
// DISPLAY CART
// ==========================================

function displayCart() {

    const cartContainer =
        document.getElementById(
            "cartContainer"
        );


    const emptyCartMessage =
        document.getElementById(
            "emptyCart"
        );


    const totalElement =
        document.getElementById(
            "cartTotal"
        );


    const buyButton =
        document.getElementById(
            "buyButton"
        );


    // If this isn't cart.html, stop here
    if (!cartContainer) {
        return;
    }


    // ======================================
    // EMPTY CART
    // ======================================

    if (cart.length === 0) {

        cartContainer.innerHTML = "";


        if (emptyCartMessage) {

            emptyCartMessage.style.display =
                "block";
        }


        if (totalElement) {

            totalElement.textContent =
                "रु0";
        }


        if (buyButton) {

            buyButton.disabled = true;
        }


        return;
    }


    // Hide empty message
    if (emptyCartMessage) {

        emptyCartMessage.style.display =
            "none";
    }


    // Enable Buy button
    if (buyButton) {

        buyButton.disabled = false;
    }


    // Clear previous cart
    cartContainer.innerHTML = "";


    let total = 0;


    // ======================================
    // DISPLAY EACH PRODUCT
    // ======================================

    cart.forEach(
        function (item, index) {

            const amount =
                item.price *
                item.quantity;


            total += amount;


            const cartItem =
                document.createElement(
                    "div"
                );


            cartItem.className =
                "cart-item";


            cartItem.innerHTML = `

                <div class="cart-item-info">

                    <h3>
                        ${item.productName}
                    </h3>

                    <p>
                        रु${item.price} / kg
                    </p>

                </div>


                <div class="cart-quantity">

                    <button
                        class="cart-minus"
                        data-index="${index}"
                    >
                        −
                    </button>


                    <span>
                        ${item.quantity}
                    </span>


                    <button
                        class="cart-plus"
                        data-index="${index}"
                    >
                        +
                    </button>

                </div>


                <div class="cart-item-amount">

                    रु${amount}

                </div>


                <button
                    class="remove-cart-item"
                    data-index="${index}"
                >
                    Remove
                </button>

            `;


            cartContainer.appendChild(
                cartItem
            );
        }
    );


    // ======================================
    // TOTAL
    // ======================================

    if (totalElement) {

        totalElement.textContent =
            "रु" + total;
    }


    // ======================================
    // CART MINUS BUTTONS
    // ======================================

    document
        .querySelectorAll(".cart-minus")
        .forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const index =
                            parseInt(
                                button.dataset.index
                            );


                        if (
                            cart[index].quantity >
                            1
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
            }
        );


    // ======================================
    // CART PLUS BUTTONS
    // ======================================

    document
        .querySelectorAll(".cart-plus")
        .forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const index =
                            parseInt(
                                button.dataset.index
                            );


                        cart[index].quantity++;


                        saveCart();

                        displayCart();
                    }
                );
            }
        );


    // ======================================
    // REMOVE BUTTONS
    // ======================================

    document
        .querySelectorAll(
            ".remove-cart-item"
        )
        .forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const index =
                            parseInt(
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
            }
        );
}


// ==========================================
// BUY NOW
// ==========================================

function initializeBuyButton() {

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


            // ==================================
            // CHECK CART
            // ==================================

            if (cart.length === 0) {

                alert(
                    "Your cart is empty."
                );

                return;
            }


            // ==================================
            // DISABLE BUTTON
            // ==================================

            buyButton.disabled = true;

            buyButton.textContent =
                "Processing...";


            try {

                // ==================================
                // CREATE FORM DATA
                // ==================================

                const formData =
                    new FormData();


                formData.append(
                    "cart",
                    JSON.stringify(cart)
                );


                // ==================================
                // SEND TO PHP
                // ==================================

                const response =
                    await fetch(
                        "php/place-order.php",
                        {
                            method: "POST",
                            body: formData
                        }
                    );


                // Get raw response first
                const responseText =
                    await response.text();


                console.log(
                    "PHP RESPONSE:",
                    responseText
                );


                // Convert response to JSON
                const result =
                    JSON.parse(
                        responseText
                    );


                // ==================================
                // SUCCESS
                // ==================================

                if (result.success) {

                    alert(
                        "Order placed successfully!\n\n" +
                        "Order ID: " +
                        result.order_id +
                        "\n" +
                        "Total: रु" +
                        result.total_amount
                    );


                    // Clear localStorage
                    localStorage.removeItem(
                        "farmfreshCart"
                    );


                    // Clear JavaScript cart
                    cart = [];


                    // Refresh cart
                    displayCart();


                } else {

                    alert(
                        "Order failed:\n" +
                        result.message
                    );
                }


            } catch (error) {

                console.error(
                    "Order error:",
                    error
                );


                alert(
                    "Something went wrong while placing the order.\n\n" +
                    "Check the browser console for details."
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
// INITIALIZE EVERYTHING
// ==========================================

document.addEventListener(
    "DOMContentLoaded",
    function () {

        initializeShop();

        displayCart();

        initializeBuyButton();

    }
);