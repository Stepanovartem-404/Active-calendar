jQuery(document).ready(function($) {
    $('.register-link').click(function(e) {
        e.preventDefault();
        var date = $(this).data('date');
        var name = prompt('Введите ваше имя:');
        var phone = prompt('Введите ваш номер телефона:');
        var email = prompt('Введите ваш email:');

        if (name && phone && email) {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'register_date',
                    name: name,
                    phone: phone,
                    email: email,
                    register_date: date
                },
                success: function(response) {
                    alert('Регистрация прошла успешно!');
                },
                error: function(error) {
                    console.log(error);
                    alert('Произошла ошибка при регистрации.');
                }
            });
        }
    });
});
