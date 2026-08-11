/* ==========================================================================
   PRODUCTS.JS — product data, rendering, filtering, and cart logic
   Plain vanilla JS, no build step. Cart persists in localStorage so it
   survives a page refresh (this is a real site running in the browser,
   not a Claude artifact, so localStorage is the right tool here).
   ========================================================================== */

// ---- 1. Product data (28 items: vegetables + fruits) --------------------
const PRODUCTS = [
  {
    id: "veg-01",
    name: "Carrot",
    cat: "vegetables",
    icon: "🥕",
    unit: "1 kg",
    price: 60,
  },
  {
    id: "veg-02",
    name: "Potato",
    cat: "vegetables",
    icon: "🥔",
    unit: "1 kg",
    price: 45,
  },
  {
    id: "veg-03",
    name: "Tomato",
    cat: "vegetables",
    icon: "🍅",
    unit: "1 kg",
    price: 70,
    oldPrice: 85,
  },
  {
    id: "veg-04",
    name: "Onion",
    cat: "vegetables",
    icon: "🧅",
    unit: "1 kg",
    price: 55,
  },
  {
    id: "veg-05",
    name: "Cauliflower",
    cat: "vegetables",
    icon: "🥦",
    unit: "1 pc",
    price: 50,
  },
  {
    id: "veg-06",
    name: "Broccoli",
    cat: "vegetables",
    icon: "🥦",
    unit: "500 g",
    price: 90,
  },
  {
    id: "veg-07",
    name: "Cucumber",
    cat: "vegetables",
    icon: "🥒",
    unit: "1 kg",
    price: 40,
  },
  {
    id: "veg-08",
    name: "Bell Pepper",
    cat: "vegetables",
    icon: "🫑",
    unit: "500 g",
    price: 95,
  },
  {
    id: "veg-09",
    name: "Cabbage",
    cat: "vegetables",
    icon: "🥬",
    unit: "1 pc",
    price: 35,
  },
  {
    id: "veg-10",
    name: "Spinach",
    cat: "vegetables",
    icon: "🥬",
    unit: "500 g",
    price: 30,
  },
  {
    id: "veg-11",
    name: "Eggplant",
    cat: "vegetables",
    icon: "🍆",
    unit: "1 kg",
    price: 65,
  },
  {
    id: "veg-12",
    name: "Pumpkin",
    cat: "vegetables",
    icon: "🎃",
    unit: "1 kg",
    price: 50,
  },
  {
    id: "veg-13",
    name: "Corn",
    cat: "vegetables",
    icon: "🌽",
    unit: "3 pc",
    price: 60,
  },
  {
    id: "veg-14",
    name: "Garlic",
    cat: "vegetables",
    icon: "🧄",
    unit: "250 g",
    price: 80,
    oldPrice: 95,
  },
  {
    id: "fru-01",
    name: "Apple",
    cat: "fruits",
    icon: "🍎",
    unit: "1 kg",
    price: 220,
  },
  {
    id: "fru-02",
    name: "Banana",
    cat: "fruits",
    icon: "🍌",
    unit: "1 dozen",
    price: 120,
  },
  {
    id: "fru-03",
    name: "Orange",
    cat: "fruits",
    icon: "🍊",
    unit: "1 kg",
    price: 180,
  },
  {
    id: "fru-04",
    name: "Mango",
    cat: "fruits",
    icon: "🥭",
    unit: "1 kg",
    price: 250,
    oldPrice: 300,
  },
  {
    id: "fru-05",
    name: "Grapes",
    cat: "fruits",
    icon: "🍇",
    unit: "500 g",
    price: 160,
  },
  {
    id: "fru-06",
    name: "Watermelon",
    cat: "fruits",
    icon: "🍉",
    unit: "1 pc",
    price: 150,
  },
  {
    id: "fru-07",
    name: "Pineapple",
    cat: "fruits",
    icon: "🍍",
    unit: "1 pc",
    price: 130,
  },
  {
    id: "fru-08",
    name: "Strawberry",
    cat: "fruits",
    icon: "🍓",
    unit: "250 g",
    price: 200,
  },
  {
    id: "fru-09",
    name: "Kiwi",
    cat: "fruits",
    icon: "🥝",
    unit: "500 g",
    price: 240,
  },
  {
    id: "fru-10",
    name: "Pomegranate",
    cat: "fruits",
    icon: "🍎",
    unit: "1 kg",
    price: 260,
  },
  {
    id: "fru-11",
    name: "Pear",
    cat: "fruits",
    icon: "🍐",
    unit: "1 kg",
    price: 210,
  },
  {
    id: "fru-12",
    name: "Papaya",
    cat: "fruits",
    icon: "🥭",
    unit: "1 pc",
    price: 90,
  },
  {
    id: "fru-13",
    name: "Lemon",
    cat: "fruits",
    icon: "🍋",
    unit: "500 g",
    price: 70,
  },
  {
    id: "fru-14",
    name: "Avocado",
    cat: "fruits",
    icon: "🥑",
    unit: "3 pc",
    price: 280,
    oldPrice: 320,
  },
];

// ----------------------
const CART_KEY = "farmfresh_cart";

function loadCart() {
  try {
    return ON.parse(localStorage.gJSetItem(CART_KEY)) || {};
  } catch {
    return {};
  }
}

function saveCart(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

let cart = loadCart();

function cartTotalItems() {
  return Object.values(cart).reduce((sum, qty) => sum + qty, 0);
}

function updateCartBadge() {
  const badge = document.getElementById("cart-count");
  if (badge) badge.textContent = cartTotalItems();
}

// ---- 3. Rendering ---------------------------------------------------------
const gridEl = document.getElementById("product-grid");
const tabsEl = document.getElementById("filters-products");
const sortEl = document.getElementById("sort-select");

let activeCategory = "all";

function formatPrice(n) {
  return `Rs. ${n}`;
}

function renderProducts() {
  let list = PRODUCTS.filter(
    (p) => activeCategory === "all" || p.cat === activeCategory,
  );

  const sortBy = sortEl ? sortEl.value : "default";
  if (sortBy === "price-asc")
    list = [...list].sort((a, b) => a.price - b.price);
  if (sortBy === "price-desc")
    list = [...list].sort((a, b) => b.price - a.price);
  if (sortBy === "name-asc")
    list = [...list].sort((a, b) => a.name.localeCompare(b.name));

  if (!list.length) {
    gridEl.innerHTML = `<div class="product-empty">No products in this category yet.</div>`;
    return;
  }

  gridEl.innerHTML = list
    .map((p) => {
      const qtyInCart = cart[p.id] || 0;
      return `
      <div class="product-card" data-id="${p.id}">
        ${p.oldPrice ? `<span class="product-badge">Sale</span>` : ""}
        <button class="product-wishlist" aria-label="Add ${p.name} to wishlist">♡</button>
        <div class="product-image">${p.icon}</div>
        <div class="product-category">${p.cat}</div>
        <div class="product-name">${p.name}</div>
        <div class="product-unit">${p.unit}</div>
        <div class="product-price-row">
          <span class="product-price">${formatPrice(p.price)}</span>
          ${p.oldPrice ? `<span class="product-price-old">${formatPrice(p.oldPrice)}</span>` : ""}
        </div>
        <div class="product-footer">
          <div class="qty-stepper">
            <button type="button" class="qty-dec" aria-label="Decrease quantity">−</button>
            <input type="number" class="qty-input" min="1" max="99" value="1" inputmode="numeric" />
            <button type="button" class="qty-inc" aria-label="Increase quantity">+</button>
          </div>
          <button type="button" class="add-to-cart-btn">
            ${qtyInCart ? `In Cart (${qtyInCart})` : "Add to Cart"}
          </button>
        </div>
      </div>`;
    })
    .join("");
}

// ---- 4. Toast ---------------------------------------------------------
let toastTimer = null;
function showToast(message) {
  let toast = document.querySelector(".cart-toast");
  if (!toast) {
    toast = document.createElement("div");
    toast.className = "cart-toast";
    document.body.appendChild(toast);
  }
  toast.textContent = message;
  toast.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove("show"), 1800);
}

// ---- 5. Event delegation (grid) ------------------------------------------
gridEl.addEventListener("click", (e) => {
  const card = e.target.closest(".product-card");
  if (!card) return;
  const id = card.dataset.id;
  const product = PRODUCTS.find((p) => p.id === id);
  const qtyInput = card.querySelector(".qty-input");

  if (e.target.classList.contains("qty-inc")) {
    qtyInput.value = Math.min(99, parseInt(qtyInput.value || "1", 10) + 1);
  }

  if (e.target.classList.contains("qty-dec")) {
    qtyInput.value = Math.max(1, parseInt(qtyInput.value || "1", 10) - 1);
  }

  if (e.target.classList.contains("add-to-cart-btn")) {
    const qty = Math.max(1, parseInt(qtyInput.value || "1", 10));
    cart[id] = (cart[id] || 0) + qty;
    saveCart(cart);
    updateCartBadge();
    showToast(`Added ${qty} × ${product.name} to cart`);

    const btn = e.target;
    btn.classList.add("added");
    btn.textContent = `In Cart (${cart[id]})`;
    setTimeout(() => btn.classList.remove("added"), 600);
  }

  if (e.target.classList.contains("product-wishlist")) {
    e.target.textContent = e.target.textContent === "♡" ? "♥" : "♡";
  }
});

// Guard against non-numeric / empty quantity input
gridEl.addEventListener("change", (e) => {
  if (e.target.classList.contains("qty-input")) {
    let val = parseInt(e.target.value, 10);
    if (isNaN(val) || val < 1) val = 1;
    if (val > 99) val = 99;
    e.target.value = val;
  }
});

// ---- 6. Category tabs ------------------------------------------------
if (tabsEl) {
  tabsEl.addEventListener("click", (e) => {
    const btn = e.target.closest("button");
    if (!btn) return;
    tabsEl
      .querySelectorAll("button")
      .forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");
    activeCategory = btn.dataset.cat;
    renderProducts();
  });
}

// ---- 7. Sort dropdown ------------------------------------------------
if (sortEl) {
  sortEl.addEventListener("change", renderProducts);
}

// ---- 8. Init ------------------------------------------------------------
updateCartBadge();
renderProducts();
