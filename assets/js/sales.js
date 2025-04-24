document.addEventListener("DOMContentLoaded", function () {
    // Initialize cart and transaction number
    const cart = [];
    let transactionNo = "TXN-" + Date.now() + "-" + Math.floor(Math.random() * 1000);

    // Display transaction number
    document.getElementById("transaction-id-display").innerText = transactionNo;
    document.getElementById("transaction-id").value = transactionNo;

    // Function to update the cart
    function updateCart() {
        const cartItemsContainer = document.getElementById('cart-items');
        cartItemsContainer.innerHTML = '';
        let grandTotal = 0;

        cart.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>
                    <button class="btn btn-link change-quantity" data-id="${item.id}" data-action="decrease">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="btn btn-link change-quantity" data-id="${item.id}" data-action="increase">+</button>
                </td>
                <td>${item.price.toFixed(2)}</td>
                <td>${item.total.toFixed(2)}</td>
                <td><button class='btn btn-danger remove-from-cart'><i class='fas fa-trash'></i>Remove</button></td>
            `;
            cartItemsContainer.appendChild(row);
            grandTotal += item.total;
        });

        document.getElementById('grand-total').innerText = grandTotal.toFixed(2);
        document.getElementById('cart-data').value = JSON.stringify(cart);
    }

    // Function to update the stock
    function updateStock(productId, quantity) {
        const row = document.querySelector(`tr[data-id='${productId}']`);
        const stockCell = row.querySelector('td:nth-child(3)');
        const currentStock = parseInt(stockCell.innerText);
        stockCell.innerText = currentStock - quantity;
    }

    // Event listener for Add to Cart button
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const productId = row.getAttribute('data-id');
            const productName = row.getAttribute('data-name');
            const productPrice = parseFloat(row.getAttribute('data-price'));
            const productStock = parseInt(row.getAttribute('data-stock'));

            const existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity += 1;
                existingItem.total = existingItem.quantity * existingItem.price;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    total: productPrice
                });
            }

            updateStock(productId, 1);
            updateCart();
        });
    });

    // Event listener for changing quantity
    document.getElementById('cart-items').addEventListener('click', function (e) {
        if (e.target.classList.contains('change-quantity')) {
            const productId = e.target.getAttribute('data-id');
            const action = e.target.getAttribute('data-action');
            const cartItem = cart.find(item => item.id === productId);

            if (action === 'increase') {
                cartItem.quantity += 1;
                updateStock(productId, 1);
            } else if (action === 'decrease' && cartItem.quantity > 1) {
                cartItem.quantity -= 1;
                updateStock(productId, -1);
            }

            cartItem.total = cartItem.quantity * cartItem.price;
            updateCart();
        }

        // Remove from cart functionality remains the same
        if (e.target.closest('.remove-from-cart')) {
            const row = e.target.closest('tr');
            const productName = row.querySelector('td:nth-child(1)').innerText;
            const cartItem = cart.find(item => item.name === productName);

            updateStock(cartItem.id, -cartItem.quantity);
            const index = cart.findIndex(item => item.name === productName);
            cart.splice(index, 1);
            updateCart();
        }
    });

    // Event listener for discount input
    document.getElementById('discount').addEventListener('input', function () {
        const discount = parseFloat(this.value) || 0;
        const grandTotalElement = document.getElementById('grand-total');
        let grandTotal = parseFloat(grandTotalElement.innerText);
        grandTotal = grandTotal - (grandTotal * (discount / 100));
        grandTotalElement.innerText = grandTotal.toFixed(2);
        updateProcessOrderButtonState();
    });

    // Event listener for cash tendered input
    document.getElementById('cashTendered').addEventListener('input', function () {
        const cashTendered = parseFloat(this.value) || 0;
        const grandTotal = parseFloat(document.getElementById('grand-total').innerText);
        const changeElement = document.getElementById('change');
        const insufficientBalanceElement = document.getElementById('insufficient-balance');

        if (cashTendered < grandTotal) {
            insufficientBalanceElement.classList.remove('d-none');
            changeElement.innerText = '0';
            document.getElementById('process-order').disabled = true;
        } else {
            insufficientBalanceElement.classList.add('d-none');
            changeElement.innerText = (cashTendered - grandTotal).toFixed(2);
            document.getElementById('process-order').disabled = false;
        }
        updateProcessOrderButtonState();
    });

    // Function to update the state of the Process Order button
    function updateProcessOrderButtonState() {
        const cashTendered = parseFloat(document.getElementById('cashTendered').value) || 0;
        const grandTotal = parseFloat(document.getElementById('grand-total').innerText);
        const processOrderButton = document.getElementById('process-order');

        processOrderButton.disabled = cashTendered < grandTotal || cashTendered === 0;
    }

    // Initial state of the Process Order button
    updateProcessOrderButtonState();

    // Event listener for Process Order button
    document.getElementById('process-order').addEventListener('click', function () {
        if (document.getElementById('insufficient-balance').classList.contains('d-none')) {
            alert("Order processed successfully!");
            document.getElementById('order-form').submit();
        } else {
            alert("Insufficient balance to complete the order.");
        }
    });

    new simpleDatatables.DataTable("#myTable");

    // Generate unique transaction number
    document.getElementById("transaction-id").value = transactionNo;
    document.getElementById("hidden-transaction-id").value = transactionNo;

    // Event listener for form submission to process the order
    document.getElementById("process-order").addEventListener("click", function () {
        let cartItems = [];
        let rows = document.querySelectorAll("#cart-items tr");
        let grandTotal = 0;

        rows.forEach(row => {
            let item = {
                id: row.dataset.id,
                quantity: row.querySelector(".quantity").innerText,
                total: row.querySelector(".total-price").innerText
            };
            grandTotal += parseFloat(item.total);
            cartItems.push(item);
        });

        document.getElementById("cart-data").value = JSON.stringify(cartItems);
        document.getElementById("order-form").submit();
    });
});

