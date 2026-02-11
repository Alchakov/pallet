document.addEventListener('DOMContentLoaded', function() {
    console.log('Custom catalog script loaded');

    // Находим все элементы с классом product-item
    const products = document.querySelectorAll('.product-item');

    products.forEach(product => {
        const priceElement = product.querySelector('.product-price');
        const actionsElement = product.querySelector('.product-actions');

        if (!priceElement || !actionsElement) {
            console.log('Price or actions element not found for product:', product);
            return;
        }

        const priceAmount = priceElement.querySelector('.product-price__amount');
        if (!priceAmount) {
            console.log('Price amount element not found for price element:', product);
            return;
        }

        const priceText = priceAmount.textContent.trim();
        console.log('Price text:', priceText);

        if (priceText === '0' || priceText === '') {
            console.log('Hiding price and actions for product:', product);

            priceElement.style.display = 'none';
            actionsElement.style.display = 'none';

            const specialButton = document.createElement('div');
            specialButton.className = 'special-button';
            specialButton.innerHTML = '<a href="#" class="btn" data-toggle="modal" data-target="#modal-buy" data-modal-title="Цена по запросу" style="height: 3.8rem;"><span class="btn__inner"><span class="btn__text">Цена по запросу</span></span></a><style>.modal-dialog { top : calc(10% - 0%);}</style>';

            // Вставляем специальную кнопку после элемента actionsElement
            product.querySelector('.product-item__actions').insertBefore(specialButton, actionsElement.nextSibling);
        }
    });
});