class LoginService {


    ajaxRequest(url, method, data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data,
                processData: method === 'GET' ? true : false,
                contentType: method === 'GET' ? 'application/x-www-form-urlencoded' : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    resolve(response);
                },
                error: (xhr) => {
                    reject(xhr);
                }
            });
        });
    }

    async login(formData) {
        try {
            loadingAllert('Proses Login...');
            const response = await this.ajaxRequest(`${appUrl}/sicici/login`, 'POST', formData);

            Swal.close();
            if (response.status === 'success') {
                await successAlert('Login Berhasil', 'Selamat Datang!');
                window.location.href = `/`;
            } else {
                errorAlert(response.message || 'Login Gagal');
            }
        } catch (error) {
            Swal.close();
            const message = error.responseJSON?.message || 'Terjadi kesalahan saat login';
            errorAlert(message);
            console.error('Login error:', error);
        }
    }

    async logout() {
        try {
            loadingAllert('Proses Logout...');
            await this.ajaxRequest(`${appUrl}/sicici/logout`, 'POST');

            Swal.close();
            await successAlert('Logout Berhasil', 'Sampai Jumpa!');
            window.location.href = `${appUrl}/login`;
        } catch (error) {
            Swal.close();
            errorAlert('Gagal Logout');
            console.error('Logout error:', error);
        }
    }
}

export default LoginService;
