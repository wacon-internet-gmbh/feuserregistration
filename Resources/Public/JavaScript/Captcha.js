const feuserRegistrationCaptcha = {
  init: function () {
    this.readAloud();
  },
  readAloud: function () {
    document.querySelectorAll('.captcha-readaloud').forEach((element) => {
      element.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        feuserRegistrationCaptcha.fetchCaptchaCode(event.target)
          .then((response) => {
            feuserRegistrationCaptcha.handleResponse(response, event.target);
          })
          .catch((error) => {
            console.error(error);
          });
      });
    });
  },
  fetchCaptchaCode: async function(target) {
    const response = await fetch(target.getAttribute('data-uri'), {
      method: 'POST',
      body: JSON.stringify({
        'returnCode': true,
      }),
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    });

    return response;
  },
  handleResponse: async function (response, targetElement) {
    if (response.ok) {
      let responseData = await response.json();
      this.read(responseData['formular']);
    } else {
      throw new Error('Network response was not ok');
    }
  },
  read: function (text) {
    if (!window.speechSynthesis) {
      console.error('Speech synthesis not supported in this browser.');
      return;
    }
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 0.5;
    utterance.lang = document.querySelector('html').getAttribute('lang');
    console.log('Reading alout in language: ' + utterance.lang);
    window.speechSynthesis.speak(utterance);
  },
};

document.addEventListener('DOMContentLoaded', function () {
  feuserRegistrationCaptcha.init();
});
