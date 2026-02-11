document.addEventListener('DOMContentLoaded', function() {
    console.log('Custom single product script loaded');

    // Находим элементы с классом product-price и product-actions
    const priceElement = document.querySelector('.product-price');
    const actionsElement = document.querySelector('.product-actions');

    if (!priceElement || !actionsElement) {
        console.log('Price or actions element not found in single product');
        return;
    }

    const priceAmount = priceElement.querySelector('.product-price__amount');
    if (!priceAmount) {
        console.log('Price amount element not found for price element:', priceElement);
        return;
    }

    const priceText = priceAmount.textContent.trim();
    console.log('Price text:', priceText);

    if (priceText === '0' || priceText === '') {
        console.log('Hiding price and actions in single product');

        priceElement.style.display = 'none';
        actionsElement.style.display = 'none';

        const stockElement = document.querySelector('.product-stock.product-stock--in-stock');
        if (!stockElement) {
            console.log('Stock element not found in single product');
            return;
        }

        const specialButton = document.createElement('div');
        specialButton.className = 'special-button';
        specialButton.innerHTML = '<a href="#" class="btn" data-toggle="modal" data-target="#modal-buy" data-modal-title="Цена по запросу"><span class="btn__inner"><span class="btn__text">Цена по запросу</span></span></a>';

        stockElement.parentNode.insertBefore(specialButton, stockElement.nextSibling);
    }
});