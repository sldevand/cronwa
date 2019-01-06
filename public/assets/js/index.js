(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

var _snackbar = require("./snackbar");

window.addEventListener('load', onInit);

function onInit() {
  initFlash();
  initErrors();
  initDelete();
}

function initFlash() {
  var flash = document.querySelector('#flash ul');
  if (null === flash) return;

  if (flash.textContent.length !== 0) {
    _snackbar.Snackbar.show('snackbar', flash.textContent);

    flash.innerHTML = '';
  }
}

function initErrors() {
  var errors = document.querySelector('.errorMessages');
  if (null === errors) return;

  if (errors.textContent.length !== 0) {
    setTimeout(function () {
      errors.innerHTML = '';
    }, 3000);
  }
}

function initDelete() {
  var deleteButtons = document.getElementsByClassName('delete');
  var dialog = document.querySelector('#dialog');

  if (!dialog.showModal) {
    dialogPolyfill.registerDialog(dialog);
  }

  if (null === deleteButtons) return;
  var _iteratorNormalCompletion = true;
  var _didIteratorError = false;
  var _iteratorError = undefined;

  try {
    for (var _iterator = deleteButtons[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
      var deleteButton = _step.value;

      deleteButton.onclick = function (event) {
        event.preventDefault();
        var href = event.target.parentNode.attributes['href'];
        dialog.showModal();
      };

      dialog.querySelector('button:not([disabled])').addEventListener('click', function () {
        dialog.close();
      });
    }
  } catch (err) {
    _didIteratorError = true;
    _iteratorError = err;
  } finally {
    try {
      if (!_iteratorNormalCompletion && _iterator.return != null) {
        _iterator.return();
      }
    } finally {
      if (_didIteratorError) {
        throw _iteratorError;
      }
    }
  }
}

},{"./snackbar":2}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Snackbar = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Snackbar =
/*#__PURE__*/
function () {
  function Snackbar() {
    _classCallCheck(this, Snackbar);
  }

  _createClass(Snackbar, null, [{
    key: "show",
    value: function show(id, text) {
      var container = document.querySelector('#' + id);
      var data = {
        message: text,
        timeout: 2000,
        actionHandler: function actionHandler() {},
        actionText: 'annuler'
      };
      container.MaterialSnackbar.showSnackbar(data);
    }
  }]);

  return Snackbar;
}();

exports.Snackbar = Snackbar;

},{}]},{},[1]);
