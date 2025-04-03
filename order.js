// Sélectionner tous les checkboxes des produits
const productCheckboxes = document.querySelectorAll('.product-item input[type="checkbox"]');
// Sélectionner tous les sélecteurs de quantité
const quantitySelects = document.querySelectorAll('.quantity-select');
// Éléments pour afficher les totaux
const subtotalElement = document.getElementById('subtotal');
const taxElement = document.getElementById('tax');
const totalElement = document.getElementById('total');
const selectedItemsContainer = document.getElementById('selected-items');

// Options de type de commande
const pickupRadio = document.getElementById('pickup');
const deliveryRadio = document.getElementById('delivery');
const pickupDetails = document.getElementById('pickup-details');
const deliveryDetails = document.getElementById('delivery-details');

// Initialiser les variables pour les calculs
let subtotal = 0;
let tax = 0;
let total = 0;
const taxRate = 0.09; // 9%

// Mettre à jour le résumé de la commande
function updateOrderSummary() {
    // Réinitialiser les totaux
    subtotal = 0;
    
    // Vider la liste des articles sélectionnés
    selectedItemsContainer.innerHTML = '';
    
    // Parcourir tous les checkboxes cochés
    productCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            // Trouver l'élément parent du produit
            const productItem = checkbox.closest('.product-item');
            // Obtenir le nom du produit
            const productName = productItem.querySelector('h3').textContent;
            // Obtenir le prix du produit (enlever le $ et convertir en nombre)
            const productPrice = parseFloat(productItem.querySelector('.price').textContent.replace('$', ''));
            // Obtenir la quantité sélectionnée
            const quantitySelect = productItem.querySelector('.quantity-select');
            const quantity = parseInt(quantitySelect.value);
            
            // Calculer le prix total de ce produit
            const itemTotal = productPrice * quantity;
            
            // Ajouter au sous-total
            subtotal += itemTotal;
            
            // Créer un élément pour l'article sélectionné
            const selectedItem = document.createElement('div');
            selectedItem.className = 'selected-item';
            selectedItem.innerHTML = `
                <span class="selected-item-name">${productName}</span>
                <span class="selected-item-qty">x${quantity}</span>
                <span class="selected-item-price">$${itemTotal.toFixed(2)}</span>
            `;
            
            // Ajouter l'article sélectionné à la liste
            selectedItemsContainer.appendChild(selectedItem);
        }
    });
    
    // Calculer la taxe et le total
    tax = subtotal * taxRate;
    total = subtotal + tax;
    
    // Mettre à jour l'affichage
    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    taxElement.textContent = `$${tax.toFixed(2)}`;
    totalElement.textContent = `$${total.toFixed(2)}`;
}

// Ajouter des écouteurs d'événements pour tous les checkboxes et sélecteurs de quantité
productCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateOrderSummary);
});

quantitySelects.forEach(select => {
    select.addEventListener('change', updateOrderSummary);
});

// Gérer les options de type de commande
pickupRadio.addEventListener('change', function() {
    if (this.checked) {
        pickupDetails.style.display = 'block';
        deliveryDetails.style.display = 'none';
    }
});

deliveryRadio.addEventListener('change', function() {
    if (this.checked) {
        pickupDetails.style.display = 'none';
        deliveryDetails.style.display = 'block';
    }
});

// Soumettre la commande


// Initialiser le résumé de la commande au chargement de la page


// Existing code remains the same...

// Soumettre la commande
// Order Submission Script with Improved Error Handling
document.querySelector('.submit-button').addEventListener('click', function(e) {
    e.preventDefault();
    
    // Validate Product Selection
    let hasSelectedProducts = false;
    const selectedItems = [];
    const productCheckboxes = document.querySelectorAll('.product-item input[type="checkbox"]');
    
    productCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            hasSelectedProducts = true;
            const productItem = checkbox.closest('.product-item');
            const productName = productItem.querySelector('h3').textContent;
            const productPrice = parseFloat(productItem.querySelector('.price').textContent.replace('$', ''));
            const quantitySelect = productItem.querySelector('.quantity-select');
            const quantity = parseInt(quantitySelect.value);
            
            selectedItems.push({
                name: productName,
                price: productPrice,
                quantity: quantity
            });
        }
    });
    
    if (!hasSelectedProducts) {
        alert('Please select at least one product to place an order.');
        return;
    }
    
    // Validate Required Fields
    const requiredFields = [
        'firstName', 'lastName', 'email', 'phone'
    ];
    
    for (let fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            alert(`Please fill in the ${field.labels[0].textContent}.`);
            field.focus();
            return;
        }
    }
    
    // Prepare Order Data
    const orderData = {
        order_type: document.getElementById('delivery').checked ? 'delivery' : 'pickup',
        first_name: document.getElementById('firstName').value,
        last_name: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        items: selectedItems,
        subtotal: parseFloat(document.getElementById('subtotal').textContent.replace('$', '')),
        tax: parseFloat(document.getElementById('tax').textContent.replace('$', '')),
        total: parseFloat(document.getElementById('total').textContent.replace('$', '')),
        special_instructions: document.getElementById('instructions')?.value || '',
        payment_method: document.querySelector('input[name="paymentMethod"]:checked').value
    };
    
    // Add Delivery or Pickup Details
    if (orderData.order_type === 'delivery') {
        const deliveryFields = ['street', 'city', 'zipcode', 'deliveryDate', 'deliveryTime'];
        
        for (let fieldId of deliveryFields) {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                alert(`Please fill in the ${field.labels[0].textContent}.`);
                field.focus();
                return;
            }
        }
        
        orderData.street = document.getElementById('street').value;
        orderData.city = document.getElementById('city').value;
        orderData.zipcode = document.getElementById('zipcode').value;
        orderData.apartment = document.getElementById('apt')?.value || '';
        orderData.delivery_date = document.getElementById('deliveryDate').value;
        orderData.delivery_time = document.getElementById('deliveryTime').value;
    } else {
        const pickupFields = ['pickupDate', 'pickupTime'];
        
        for (let fieldId of pickupFields) {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                alert(`Please fill in the ${field.labels[0].textContent}.`);
                field.focus();
                return;
            }
        }
        
        orderData.pickup_date = document.getElementById('pickupDate').value;
        orderData.pickup_time = document.getElementById('pickupTime').value;
    }
    
    // Debug: Log the order data before sending
    console.log('Sending Order Data:', orderData);
    
    // Send Order to Backend
    fetch('orderp.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => {
        console.log('Response Status:', response.status);
        
        // Important: Handle different response statuses
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Server Response:', data);
        
        if (data.success) {
            alert(`Your order has been placed successfully! Order #${data.order_id}`);
            // Optional: Reset form or redirect
            // window.location.href = 'confirmation.php';
        } else {
            alert('Order placement failed: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Complete Error Details:', error);
        alert('An error occurred while processing your order. Please try again. Check console for details.');
    });
});

// Initialize order summary on page load
document.addEventListener('DOMContentLoaded', function() {
    const productCheckboxes = document.querySelectorAll('.product-item input[type="checkbox"]');
    const quantitySelects = document.querySelectorAll('.quantity-select');
    
    function updateOrderSummary() {
        // Your existing updateOrderSummary logic here
        // (I'll use the original implementation from the previous code)
    }
    
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateOrderSummary);
    });
    
    quantitySelects.forEach(select => {
        select.addEventListener('change', updateOrderSummary);
    });
    
    updateOrderSummary();
});

document.addEventListener('DOMContentLoaded', updateOrderSummary);