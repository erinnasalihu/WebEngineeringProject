let slider = document.querySelector('.slider');
let list = document.querySelector('.list');
let prev = document.getElementById('prev');
let next = document.getElementById('next');
let items = document.querySelectorAll('.list .item');
let count = items.length;
let active = 0; 

function runCarousel() {
    
    let width_item = items[0].offsetWidth;

   
    prev.style.display = (active === 0) ? 'none' : 'block';
    next.style.display = (active === count - 1) ? 'none' : 'block';

    
    document.querySelector('.item.active')?.classList.remove('active');

    
    items[active].classList.add('active');

    
    let leftTransform = width_item * active * -1;
    list.style.transform = `translateX(${leftTransform}px)`;
}


next.onclick = () => {
    if (active < count - 1) active++;
    runCarousel();
};

prev.onclick = () => {
    if (active > 0) active--;
    runCarousel();
};


runCarousel();
