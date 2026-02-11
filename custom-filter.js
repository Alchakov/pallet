document.addEventListener('DOMContentLoaded', function() {
    var filterToggle = document.getElementById('filterToggle');
    var filterContent = document.getElementById('filterContent');

    // Проверяем ширину экрана
    function checkScreenSize() {
        if (window.innerWidth > 738) { // Установите ширину, при которой фильтры должны быть открыты
            filterContent.style.display = 'block'; // Всегда открыто на ПК
            filterToggle.removeEventListener('click', toggleFilter); // Удаляем обработчик клика
        } else {
            filterContent.style.display = 'none'; // Скрыто на мобильных устройствах
            filterToggle.addEventListener('click', toggleFilter); // Добавляем обработчик клика
        }
    }

    // Функция для переключения фильтров
    function toggleFilter() {
        if (filterContent.style.display === 'none' || filterContent.style.display === '') {
            filterContent.style.display = 'block';
            this.querySelector('.filter-icon').textContent = '⏶'; // Изменение иконки на "вверх"
        } else {
            filterContent.style.display = 'none';
            this.querySelector('.filter-icon').textContent = '⏷'; // Изменение иконки на "вниз"
        }
    }

    // Проверяем размер экрана при загрузке
    checkScreenSize();

    // Проверяем размер экрана при изменении размера окна
    window.addEventListener('resize', checkScreenSize);
});