'use strict';

// Integration: load this external file with defer (allowed by V2 script-src 'self').
// Place scenario, message, inspect and feedback hooks inside #phishing-simulator.
(function () {
    function initializePhishing() {
        const simulator = document.querySelector('#phishing-simulator');
        if (!simulator) return;

        const version = simulator.dataset.version;
        if (version !== 'v1' && version !== 'v2') return;

        const scenarios = simulator.querySelectorAll('[data-phishing-scenario="delivery"]');
        const message = simulator.querySelector('#phishing-message');
        const inspect = simulator.querySelector('[data-phishing-action="inspect"]');
        const feedback = simulator.querySelector('#phishing-feedback');
        if (!scenarios.length || !message || !inspect || !feedback) return;

        // Fixed, fictional data only. The .invalid address is plain text, never a link.
        const deliveryMessage = [
            'MÔ PHỎNG HỌC TẬP — Chỉ dùng dữ liệu giả, không nhập thông tin thật.',
            '[Giao hàng CÁ\'S HOA] Đơn hoa DEMO-001 đang bị tạm giữ.',
            'Hãy xác minh trong 10 phút, nếu không đơn sẽ bị hủy.',
            'Truy cập https://cas-hoa-giao-hang.invalid/xac-minh và cung cấp mật khẩu,',
            'mã OTP cùng thông tin thanh toán để nhận hàng.'
        ].join(' ');
        const warningSigns = [
            'Tên miền không chính thức: cas-hoa-giao-hang.invalid chỉ là địa chỉ giả lập, không phải website chính thức của cửa hàng.',
            'Thúc ép xác minh gấp: thời hạn 10 phút và lời đe dọa hủy đơn tạo áp lực để bạn hành động vội vàng.',
            'Yêu cầu thông tin nhạy cảm: mật khẩu, mã OTP và thông tin thanh toán không được cung cấp qua tin nhắn giao hàng.'
        ];

        feedback.setAttribute('role', 'status');
        feedback.setAttribute('aria-live', 'polite');
        feedback.setAttribute('aria-atomic', 'true');

        function showDelivery() {
            message.textContent = deliveryMessage;
            message.hidden = false;
            feedback.textContent = '';
            feedback.hidden = false;
        }

        // Both versions start with just the simulated message, without analysis.
        showDelivery();
        scenarios.forEach(function (scenario) {
            scenario.addEventListener('click', function (event) {
                event.preventDefault();
                showDelivery();
            });
        });

        inspect.addEventListener('click', function (event) {
            // Also prevent accidental form submission or anchor navigation in the UI.
            event.preventDefault();
            feedback.textContent = '';
            feedback.hidden = false;

            if (version === 'v1') {
                feedback.textContent = 'V1 chỉ hiển thị thông báo mô phỏng để bạn tự xem xét. Chuyển sang V2 và bấm Kiểm tra để xem hướng dẫn nhận diện.';
                return;
            }

            const summary = document.createElement('p');
            summary.textContent = 'Thông báo mô phỏng có 3 dấu hiệu nghi vấn:';
            const list = document.createElement('ul');
            warningSigns.forEach(function (sign) {
                const item = document.createElement('li');
                item.textContent = sign;
                list.appendChild(item);
            });
            const recommendation = document.createElement('p');
            recommendation.textContent = 'Không mở liên kết trong tin nhắn và không cung cấp thông tin nhạy cảm. Hãy tự nhập địa chỉ website chính thức hoặc liên hệ cửa hàng qua kênh đã biết để xác minh đơn hàng. Đây chỉ là mô phỏng học tập.';
            feedback.appendChild(summary);
            feedback.appendChild(list);
            feedback.appendChild(recommendation);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePhishing, { once: true });
    } else {
        initializePhishing();
    }
}());
