let cart = JSON.parse(localStorage.getItem("cart")) || [];

function addToCart(productId, productName, price) {

    const existingProduct = cart.find(item => item.productId === productId);

    if (existingProduct) {
        existingProduct.quantity++;
    } else {
        cart.push({
            productId: productId,
            productName: productName,
            price: price,
            quantity: 1
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));

    alert(productName + " added to cart!");

    console.log(cart);
}