// Mobile menu toggle
const toggle = document.querySelector('.menu-toggle');
const links = document.getElementById('nav-links');
if (toggle && links) {
  toggle.addEventListener('click', () => {
    const expanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!expanded));
    links.classList.toggle('show');
  });
}

// Smooth scroll for internal links
document.addEventListener('click', (e) => {
  const target = e.target;
  if (target && target.matches('a[href^="#"]')) {
    const id = target.getAttribute('href');
    const el = document.querySelector(id);
    if (el) {
      e.preventDefault();
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      if (links && links.classList.contains('show')) {
        links.classList.remove('show');
        toggle.setAttribute('aria-expanded', 'false');
      }
    }
  }
});

// Carousel Slider JavaScript
let nextDom = document.getElementById('next');
let prevDom = document.getElementById('prev');

let carouselDom = document.querySelector('.carousel');
if (carouselDom) {
    let SliderDom = carouselDom.querySelector('.carousel .list');
    let thumbnailBorderDom = document.querySelector('.carousel .thumbnail');
    let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');
    let timeDom = document.querySelector('.carousel .time');

    thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
    let timeRunning = 3000;
    let timeAutoNext = 15000;

    nextDom.onclick = function(){
        showSlider('next');    
    }

    prevDom.onclick = function(){
        showSlider('prev');    
    }

    let runTimeOut;
    let runNextAuto = setTimeout(() => {
        nextDom.click();
    }, timeAutoNext);

    function showSlider(type){
        let SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
        let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');
        
        if(type === 'next'){
            SliderDom.appendChild(SliderItemsDom[0]);
            thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
            carouselDom.classList.add('next');
        }else{
            SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
            thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
            carouselDom.classList.add('prev');
        }
        
        clearTimeout(runTimeOut);
        runTimeOut = setTimeout(() => {
            carouselDom.classList.remove('next');
            carouselDom.classList.remove('prev');
        }, timeRunning);

        clearTimeout(runNextAuto);
        runNextAuto = setTimeout(() => {
            nextDom.click();
        }, timeAutoNext);
    }
}


// القائمة المنسدلة للمناطق
const dropdown = document.querySelector('.dropdown');
const dropbtn = dropdown?.querySelector('.dropbtn');

if (dropdown && dropbtn) {
  // عند الضغط على زر القائمة فقط
  dropbtn.addEventListener('click', function (e) {
    e.preventDefault(); // يمنع التنقل عن الزر نفسه فقط
    dropdown.classList.toggle('show');
  });

  // إغلاق القائمة إذا ضغط المستخدم خارجها
  window.addEventListener('click', function (e) {
    if (!e.target.closest('.dropdown')) {
      dropdown.classList.remove('show');
    }
  });
}
