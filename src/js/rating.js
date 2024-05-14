const stars = document.querySelectorAll('.star');
let currentRating = 0;

stars.forEach(star => {
    star.addEventListener('click', () => {
        currentRating = parseInt(star.getAttribute('data-value'));
        resetStars();
        highlightStars();
        document.getElementById('rating').value = currentRating;
    });

    star.addEventListener('mouseover', () => {
        const value = parseInt(star.getAttribute('data-value'));
        resetStars();
        for (let i = 0; i < value; i++) {
            stars[i].classList.add('active');
        }
        if (value % 1 !== 0) {
            stars[Math.floor(value)].classList.add('active');
            stars[Math.floor(value)].classList.add('star-half');
        }
    });

    star.addEventListener('mouseout', () => {
        resetStars();
        highlightStars();
    });
});

function resetStars() {
    stars.forEach(star => {
        star.classList.remove('active');
        star.classList.remove('star-half');
    });
}

function highlightStars() {
    for (let i = 0; i < currentRating; i++) {
        stars[i].classList.add('active');
    }
    if (currentRating % 1 !== 0) {
        stars[Math.floor(currentRating)].classList.add('active');
        stars[Math.floor(currentRating)].classList.add('star-half');
    }
}

function submitRating() {
    const rating = document.getElementById('rating').value;
    document.getElementById('result').innerText = `You rated: ${rating}`;
}
