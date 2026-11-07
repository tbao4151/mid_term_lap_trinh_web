'use strict';
// Educational browser-only simulation. No requests, credentials or personal data.
(function () {
  function initializePhishing() {
    const simulator = document.querySelector('#phishing-simulator');
    if (!simulator || !['v1', 'v2'].includes(simulator.dataset.version)) return;
    const version = simulator.dataset.version;
    const scenario = simulator.querySelector('[data-phishing-scenario="delivery"]');
    const message = simulator.querySelector('#phishing-message');
    const button = simulator.querySelector('[data-phishing-action="inspect"]');
    const feedback = simulator.querySelector('#phishing-feedback');
    if (!scenario || !message || !button || !feedback) return;
    const original = Array.from(feedback.childNodes, node => node.cloneNode(true));
    scenario.addEventListener('click', event => {
      event.preventDefault();
      message.hidden = false;
      feedback.replaceChildren(...original.map(node => node.cloneNode(true)));
    });
    button.addEventListener('click', event => {
      event.preventDefault();
      feedback.replaceChildren();
      if (version === 'v1') {
        const note = document.createElement('p');
        note.textContent = 'V1 để người học tự đánh giá thông báo giả lập. Chuyển sang V2 để xem phân tích dấu hiệu nghi vấn.';
        feedback.appendChild(note);
        return;
      }
      const summary = document.createElement('p');
      summary.textContent = 'Ba dấu hiệu cần xác minh:';
      const list = document.createElement('ul');
      list.className = 'phishing-signals';
      for (const sign of [
        'Tên miền xac-minh-giao-hoa.example.test chỉ là địa chỉ minh họa, chưa được xác nhận là kênh chính thức.',
        'Tin nhắn thúc ép xác minh trong 15 phút để tạo áp lực.',
        'Khoản phí giao hoa 12.000 đ chưa được kiểm chứng với đơn hàng.'
      ]) {
        const item = document.createElement('li');
        item.textContent = sign;
        list.appendChild(item);
      }
      const advice = document.createElement('p');
      advice.textContent = 'Không mở địa chỉ trong tin nhắn, không cung cấp mật khẩu, OTP hoặc dữ liệu thanh toán. Chủ động liên hệ cửa hàng qua kênh đã biết để xác minh. Đây là mô phỏng học tập.';
      feedback.append(summary, list, advice);
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePhishing, { once: true });
  } else {
    initializePhishing();
  }
}());
