// Kullanıcıları çeken fonksiyon
function fetchData() {
    fetch('http://localhost/leanphp/users')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const dataList = document.getElementById('dataList');
            dataList.innerHTML = '';  // Listeyi temizle
            data.data.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `${item.username} - ${item.password} - ${item.email}`;
                dataList.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Fetch error:', error.message);
        });
}

// Kullanıcı ekleyen fonksiyon
function postData(user) {
    fetch('http://localhost/leanphp/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(user)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Data successfully posted:', data);
        fetchData();  // Veriyi gönderdikten sonra listeyi güncelle
    })
    .catch(error => {
        console.error('Post error:', error.message);
    });
}

// Form gönderildiğinde POST isteği yapma
const form = document.getElementById('userForm');

form.addEventListener('submit', function(event) {
    event.preventDefault();  // Formun varsayılan gönderimini engelle

    const formData = {
        username: form.username.value,
        password: form.password.value,
        email: form.email.value,
        avatar_url: form.avatar_url.value,
    };

    postData(formData);
});

// Sayfa yüklendiğinde verileri çek
fetchData();
