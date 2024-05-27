const apiUrl = 'http://localhost/leanphp';
const userAgent = 'LeanPHPRestClient';

async function request(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'User-Agent': userAgent
        }
    };
    if (data) {
        options.body = JSON.stringify(data);
    }

    const response = await fetch(url, options);
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
}

async function registerUser() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;
    try {
        const data = await request(`${apiUrl}/register`, 'POST', { username, password, email });
        console.log('Registration successful', data);
        alert('Kayıt başarılı!');
    } catch (error) {
        console.error('Registration failed', error);
        alert('Kayıt başarısız!');
    }
}

async function loginUser() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    try {
        const data = await request(`${apiUrl}/login`, 'POST', { username, password });
        sessionStorage.setItem('token', data.token);
        console.log('Login successful', data);
        console.log('Login successful', data);
        alert('Giriş başarılı!');
        fetchUsers();  // Giriş başarılı olduktan sonra kullanıcı listesini çek
    } catch (error) {
        console.error('Login failed', error);
        alert('Giriş başarısız!');
    }
}


async function fetchData() {
    try {
        const response = await fetch(`${apiUrl}/api/users`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'User-Agent': 'LeanPHPRestClient'
            }
        });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log(data);  // Verinin tamamını konsola yazdırın.
        const dataList = document.getElementById('dataList');
        dataList.innerHTML = '';  // Listeyi temizle
        // Dönen veri yapısını kontrol ederek doğru yolu belirleyin.
        (data.data || data).forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.username} - ${item.email}`;
            dataList.appendChild(li);
        });
    } catch (error) {
        console.error('Failed to fetch users', error);
        alert('Kullanıcıları yüklerken bir hata oluştu!');
    }
}

// fetchData();

async function getUserDetails(userId) {
    try {
        const data = await request(`${apiUrl}/api/user/${userId}`, 'GET', null, {
        });
        console.log('User details:', data);
        alert(`Kullanıcı Detayları: ${data.username}`);
    } catch (error) {
        console.error('Failed to fetch user details', error);
        alert('Kullanıcı detayları yüklenirken bir hata oluştu!');
    }
}

async function requestPasswordReset(email) {
    try {
        const data = await request(`${apiUrl}/newpassword`, 'POST', { email });
        console.log('Password reset link sent:', data);
        alert('Şifre sıfırlama bağlantısı gönderildi!');
    } catch (error) {
        console.error('Password reset request failed', error);
        alert('Şifre sıfırlama isteği başarısız oldu!');
    }
}

async function resetPassword(token, newPassword) {
    try {
        const url = `${apiUrl}/resetPassword/${token}`;
        const data = await request(url, 'POST', { newPassword });
        console.log('Password successfully reset:', data);
        alert('Şifre başarıyla sıfırlandı!');
    } catch (error) {
        console.error('Password reset failed', error);
        alert('Şifre sıfırlama başarısız oldu!');
    }
}
