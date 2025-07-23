function navigateTo(page) {
    window.location.href = page;
}

function fetchUserProfile() {
    fetch('get-profile.php')  // PHP file that returns user data as JSON
        .then(response => response.json())
        .then(data => {
            document.getElementById('user-id').textContent = data.id;
            document.getElementById('user-name').textContent = data.name;
            document.getElementById('user-email').textContent = data.email;
            document.getElementById('user-phone').textContent = data.phone;
            document.getElementById('user-address').textContent = data.address;
        })
        .catch(error => console.error('Error fetching user profile:', error));
}


