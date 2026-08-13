let cart = JSON.parse(localStorage.getItem("farmfreshCart")) || [];


document.querySelectorAll(".product-card").forEach((card) => {

    const minusButton = card.querySelector(
        'button[aria-label="decrease"]'
    );

    const plusButton = card.querySelector(
        'button[aria-label="increase"]'
    );

    const quantityDisplay = card.querySelector(".qty-selector span");

    const addButton = card.querySelector(".add-cart-btn");


    if (!minusButton || !plusButton || !addButton) {
        return;
    }


    let quantity = parseInt(quantityDisplay.textContent);


    // Increase quantity
    plusButton.addEventListener("click", () => {

        const stock = parseInt(card.dataset.stock);

        if (quantity < stock) {
            quantity++;
            quantityDisplay.textContent = quantity;
        }

    });


    // Decrease quantity
    minusButton.addEventListener("click", () => {

        if (quantity > 1) {
            quantity--;
            quantityDisplay.textContent = quantity;
        }

    });


    // Add product to cart
    addButton.addEventListener("click", () => {

        const productId = parseInt(card.dataset.productId);
        const productName = card.dataset.productName;
        const price = parseFloat(card.dataset.price);
        const stock = parseInt(card.dataset.stock);


        const existingProduct = cart.find(
            item => item.productId === productId
        );


        if (existingProduct) {

            if (existingProduct.quantity + quantity <= stock) {

                existingProduct.quantity += quantity;

            } else {

                alert("Not enough stock available.");
                return;

            }

        } else {

            cart.push({
                productId: productId,
                productName: productName,
                price: price,
                quantity: quantity
            });

        }


        localStorage.setItem(
            "farmfreshCart",
            JSON.stringify(cart)
        );


        addButton.textContent = "Added ✓";


        setTimeout(() => {
            addButton.textContent = "Add to Cart";
        }, 1200);


        console.log("Current cart:", cart);

    });

});