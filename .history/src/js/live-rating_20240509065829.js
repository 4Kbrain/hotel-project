function fetchReviews() {
    fetch('../get_reviews.php')
    .then(response => response.json())
    .then(reviews => {
        let reviewContainer = document.getElementById('reviews');
        reviewContainer.innerHTML = '';

        reviews.forEach(review => {
            let div = document.createElement('div');
            div.innerHTML = `<p><strong>NIK: </strong>${review.NIK}</p><p><strong>Rating: </strong>${review.bintang}</p><p><strong>Review: </strong>${review.review}</p>`;
            reviewContainer.appendChild(div);
        });
    });
}

// Call the fetchReviews function initially and then every 5 seconds
fetchReviews();
setInterval(fetchReviews, 5000);