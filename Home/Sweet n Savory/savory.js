let slider = document.querySelector('.slider');
let list = document.querySelector('.list');
let prev = document.getElementById('prev');
let next = document.getElementById('next');
let items = document.querySelectorAll('.list .item');
let count = items.length;
let active = 0; // Start from the first item

function runCarousel() {
    // Ensure width is dynamically calculated
    let width_item = items[0].offsetWidth;

    // Update button visibility
    prev.style.display = (active === 0) ? 'none' : 'block';
    next.style.display = (active === count - 1) ? 'none' : 'block';

    // Remove 'active' from previously active item
    document.querySelector('.item.active')?.classList.remove('active');

    // Add 'active' to the new active item
    items[active].classList.add('active');

    // Translate the list to show the active item
    let leftTransform = width_item * active * -1;
    list.style.transform = `translateX(${leftTransform}px)`;
}

// Event listeners for next and previous buttons
next.onclick = () => {
    if (active < count - 1) active++;
    runCarousel();
};

prev.onclick = () => {
    if (active > 0) active--;
    runCarousel();
};

// Initialize the carousel
runCarousel();
