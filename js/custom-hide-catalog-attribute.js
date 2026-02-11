// Выберите все блоки product-attributes
const productAttributesBlocks = document.querySelectorAll('.product-attributes');

productAttributesBlocks.forEach(block => {
    const attributesItems = block.querySelectorAll('.product-attributes__item');

    if (attributesItems.length > 2) {
        // Создайте новый элемент details
		 
        const detailsElement = document.createElement('details');
        const summaryElement = document.createElement('summary');
        summaryElement.textContent = 'Все характеристики';

        // Установите размер шрифта для текста "ЕЩЕ"
        summaryElement.style.fontSize = '15px';

        // Убедимся, что summary виден
        summaryElement.style.display = 'block';
        summaryElement.style.cursor = 'pointer';
        summaryElement.style.userSelect = 'none';
		summaryElement.style.marginBottom = '10px';
		
        // Убедимся, что details имеет отступ сверху
        detailsElement.style.marginTop = '10px';
		

        detailsElement.appendChild(summaryElement);

		
		
        // Переместите все элементы, кроме первых двух, внутрь details
        for (let i = 2; i < attributesItems.length; i++) {
            detailsElement.appendChild(attributesItems[i]);
        }

        // Убедимся, что ширина .product-attributes__value равна auto
        attributesItems.forEach(item => {
            const valueElement = item.querySelector('.product-attributes__value');
            if (valueElement) {
                valueElement.style.width = 'auto';
            }
        });

        // Вставьте элемент details после второго элемента
        attributesItems[1].parentNode.insertBefore(detailsElement, attributesItems[1].nextSibling);

        // Добавьте обработчик события для изменения текста кнопки
        detailsElement.addEventListener('toggle', () => {
            if (detailsElement.open) {
                summaryElement.textContent = 'Свернуть';
            } else {
                summaryElement.textContent = 'Все характеристики.';
            }
        });
    }
});