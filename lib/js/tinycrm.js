(function ($) {
    var userPhone = '078897501',
        storage = [
             { name: 'Аркадий', phone: '24171230' }
        ];

    storage.forEach(function (contact) {
        var phoneLink = '<span class="btn-link make-call">' + contact.phone + '</span>';

        $('<tr></tr>')
            .append('<td>' + contact.name + '</td>')
            .append('<td width="1%" nowrap>' + phoneLink + '</td>')
            .appendTo('#contacts');
    });

    pz.onEvent(function (event) {
        switch (true) {
            case event.isIncoming():
                if (event.to === userPhone) {
                    showCard(event.from);
                }
                break;
            case event.isTransfer():
                if (findByPhone(storage, event.from)) {
                    pz.transfer(event.callID, userPhone);
                }
                break;
            case event.isHistory():
                if (event.to === userPhone || event.from === userPhone) {
                    appendCallInfo(event);
                }
                break;
            case event.isSmsStatus():
                var phone = event.to;

                if (event.result == '0') {
                    showMessage('СМС отправляется на номер ' + phone);
                }
                else if (event.result == '1') {
                    showMessage('СМС успешно отправлена на номер ' + phone);
                }
                else {
                    showError('Не удалось отправить СМС на номер ' + phone)
                }
                break;
        }
    });

    var lastDisconnectCode = -1;
    pz.onDisconnect(function(error) {
        if (error.code == 1000)// Стандартный код отключения WebSocket'а, не ошибка, пропускаем
            return;

        if (lastDisconnectCode == error.code)// Если ошибка повторяется, то не спамим её пользователю
            return;
        lastDisconnectCode = error.code;

        var appError = pz.APPLICATION_ERRORS[error.code];
        var errorText = appError ? appError.ru : 'Непредвиденная ошибка подключения';
        showError(errorText);
    });

    $('#button').on('click', function() {
        if ($(this).text() === 'Соединить') {
            pz.connect({
                user_phone: userPhone,  // Номер менеджера
                host: "ws://localhost:10150", // Адрес сервера
                client_id: '10VM50',  // Пароль
                client_type: 'jsapi'    // Тип приложения
            });
        } else {
            pz.disconnect();
        }
    });

    $('#button-send-sms').on('click', function() {
        if (pz.isConnected()) {
            var phone = $('input[name=sms-phone]').val();
            var text = $('input[name=sms-text]').val();
            pz.sms(phone, text);
        } else {
            showError("Нет соединения");
        }
    });

    setInterval(function() {
        if (pz.isConnected()) {
            $('#indicator')
                .removeClass('badge-important')
                .addClass('badge-success')
                .text('Соединение установлено');
            $('#button').text('Разъединить');
        } else {
            $('#indicator')
                .removeClass('badge-success')
                .addClass('badge-important')
                .text('Нет соединения');
            $('#button').text('Соединить');
        }
    }, 1000);

    $('body').on('click', '.make-call', function() {
        pz.call($(this).text());
    });

    function sanitizePhone(phone)
    {
        return phone.replace(/\D/g, '').slice(-10);
    }

    function findByPhone(contacts, phone) {
        return contacts.filter(function (contact) {
            return sanitizePhone(contact.phone) === sanitizePhone(phone);
        }).shift();
    }

    function showCard(phone) {
        var contact = findByPhone(storage, phone);

        var contactName;
        var event;
        if (contact) {
            contactName = contact.name;
            event = 'foundContact';
        }
        else {
            event = 'notFoundContact';
        }

        pzNoty.showNotificationContact(phone, null, 'incoming', contactName, null, event);
    }
    
    function showError(text) {
        pzNoty.showNotificationErrorMessage(text)
    }

    function showMessage(text) {
        pzNoty.showNotificationMessage(text)
    }

    moment.lang('ru');

    function appendCallInfo(event) {
        var direction = event.direction === '1' ? 'Исходящий' : 'Входящий',
            phone     = event.direction === '1' ? event.to : event.from,
            contact   = findByPhone(storage, phone),
            name      = contact ? contact.name : '',
            fromNow   = moment.unix(parseInt(event.start)).fromNow(),
            duration  = moment.duration(parseInt(event.duration), "seconds").humanize();

        $('<tr></tr>')
            .append('<td>' + direction + '</td>')
            .append('<td>' + phone + '</td>')
            .append('<td>' + name + '</td>')
            .append('<td>' + fromNow + '</td>')
            .append('<td>' + duration + '</td>')
            .appendTo('#history');
    }
}(jQuery));