let slider = document.querySelector('.slider');
let list = document.querySelector('.list');
let prev = document.getElementById('prev');
let next = document.getElementById('next');
let items = document.querySelectorAll('.list .item');
let count = items.length;
let active = 0; // Start from the first item
let isAnimating = false; // Prevent rapid clicks

// Function to update the carousel
function runCarousel() {
    if (isAnimating) return; // Block rapid clicks
    isAnimating = true;

    // Ensure width is dynamically calculated
    let width_item = items[0].offsetWidth;

    // Update button visibility
    prev.style.display = (active === 0) ? 'none' : 'block';
    next.style.display = (active === count - 1) ? 'none' : 'block';

    // Remove 'active' class from the current active item
    let oldActive = document.querySelector('.item.active');
    if (oldActive) oldActive.classList.remove('active');

    // Add 'active' class to the new active item
    items[active].classList.add('active');

    // Translate the list to show the active item
    let leftTransform = width_item * active * -1;
    list.style.transform = `translateX(${leftTransform}px)`;

    // Allow animations to complete before enabling interactions
    setTimeout(() => {
        isAnimating = false;
    }, 500); // Matches the CSS transition duration
}

// Event listeners for navigation buttons
next.onclick = () => {
    if (active < count - 1) active++;
    runCarousel();
};

prev.onclick = () => {
    if (active > 0) active--;
    runCarousel();
};

// Recalculate layout on window resize
window.onresize = runCarousel;

// Initialize the carousel on page load
window.onload = () => {
    runCarousel();
};
