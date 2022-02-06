(self["webpackChunk"] = self["webpackChunk"] || []).push([["exchange"],{

/***/ "./assets/exchange.js":
/*!****************************!*\
  !*** ./assets/exchange.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");

__webpack_require__(/*! core-js/modules/es.promise.js */ "./node_modules/core-js/modules/es.promise.js");

__webpack_require__(/*! core-js/modules/es.parse-int.js */ "./node_modules/core-js/modules/es.parse-int.js");

__webpack_require__(/*! core-js/modules/es.object.define-property.js */ "./node_modules/core-js/modules/es.object.define-property.js");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var Exchange = /*#__PURE__*/function () {
  "use strict";

  function Exchange() {
    var _this = this;

    _classCallCheck(this, Exchange);

    var formName = 'exchange_form';
    this.amountField = document.getElementById("".concat(formName, "_amount"));
    this.primaryCurrency = document.getElementById("".concat(formName, "_primaryCurrency"));
    this.targetCurrency = document.getElementById("".concat(formName, "_targetCurrency"));
    this.amountField.addEventListener('change', function () {
      fetch('/public/get-conversion-result', {
        method: 'POST',
        body: JSON.stringify({
          'primaryCurrency': _this.primaryCurrency.value,
          'targetCurrency': _this.targetCurrency.value,
          amount: parseInt(_this.amountField.value)
        })
      }).then(function (result) {
        return result.json();
      }).then(function (result) {
        return console.log(result);
      });
    });
  }

  _createClass(Exchange, [{
    key: "showConversionResult",
    value: function showConversionResult() {
      console.log(this.primaryCurrency.value);
    }
  }]);

  return Exchange;
}();

var exchange = new Exchange();

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_internals_a-constructor_js-node_modules_core-js_internals_array--7779d0","vendors-node_modules_core-js_modules_es_parse-int_js-node_modules_core-js_modules_es_promise_js"], () => (__webpack_exec__("./assets/exchange.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiZXhjaGFuZ2UuanMiLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztJQUFNQTs7O0FBQ0Ysc0JBQWM7QUFBQTs7QUFBQTs7QUFDVixRQUFNQyxRQUFRLEdBQUcsZUFBakI7QUFDQSxTQUFLQyxXQUFMLEdBQW1CQyxRQUFRLENBQUNDLGNBQVQsV0FBMkJILFFBQTNCLGFBQW5CO0FBQ0EsU0FBS0ksZUFBTCxHQUF1QkYsUUFBUSxDQUFDQyxjQUFULFdBQTJCSCxRQUEzQixzQkFBdkI7QUFDQSxTQUFLSyxjQUFMLEdBQXNCSCxRQUFRLENBQUNDLGNBQVQsV0FBMkJILFFBQTNCLHFCQUF0QjtBQUNBLFNBQUtDLFdBQUwsQ0FBaUJLLGdCQUFqQixDQUFrQyxRQUFsQyxFQUE0QyxZQUFNO0FBQzlDQyxNQUFBQSxLQUFLLENBQUMsK0JBQUQsRUFBa0M7QUFDbkNDLFFBQUFBLE1BQU0sRUFBRSxNQUQyQjtBQUVuQ0MsUUFBQUEsSUFBSSxFQUFFQyxJQUFJLENBQUNDLFNBQUwsQ0FBZTtBQUNqQiw2QkFBbUIsS0FBSSxDQUFDUCxlQUFMLENBQXFCUSxLQUR2QjtBQUVqQiw0QkFBa0IsS0FBSSxDQUFDUCxjQUFMLENBQW9CTyxLQUZyQjtBQUdqQkMsVUFBQUEsTUFBTSxFQUFFQyxRQUFRLENBQUMsS0FBSSxDQUFDYixXQUFMLENBQWlCVyxLQUFsQjtBQUhDLFNBQWY7QUFGNkIsT0FBbEMsQ0FBTCxDQVFLRyxJQVJMLENBUVUsVUFBQUMsTUFBTTtBQUFBLGVBQUlBLE1BQU0sQ0FBQ0MsSUFBUCxFQUFKO0FBQUEsT0FSaEIsRUFTS0YsSUFUTCxDQVNVLFVBQUFDLE1BQU07QUFBQSxlQUFJRSxPQUFPLENBQUNDLEdBQVIsQ0FBWUgsTUFBWixDQUFKO0FBQUEsT0FUaEI7QUFVSCxLQVhEO0FBYUg7Ozs7V0FFRCxnQ0FBdUI7QUFDbkJFLE1BQUFBLE9BQU8sQ0FBQ0MsR0FBUixDQUFZLEtBQUtmLGVBQUwsQ0FBcUJRLEtBQWpDO0FBRUg7Ozs7OztBQUdMLElBQU1RLFFBQVEsR0FBRyxJQUFJckIsUUFBSixFQUFqQiIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9leGNoYW5nZS5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJjbGFzcyBFeGNoYW5nZSB7XG4gICAgY29uc3RydWN0b3IoKSB7XG4gICAgICAgIGNvbnN0IGZvcm1OYW1lID0gJ2V4Y2hhbmdlX2Zvcm0nO1xuICAgICAgICB0aGlzLmFtb3VudEZpZWxkID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoYCR7Zm9ybU5hbWV9X2Ftb3VudGApO1xuICAgICAgICB0aGlzLnByaW1hcnlDdXJyZW5jeSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGAke2Zvcm1OYW1lfV9wcmltYXJ5Q3VycmVuY3lgKTtcbiAgICAgICAgdGhpcy50YXJnZXRDdXJyZW5jeSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGAke2Zvcm1OYW1lfV90YXJnZXRDdXJyZW5jeWApO1xuICAgICAgICB0aGlzLmFtb3VudEZpZWxkLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsICgpID0+IHtcbiAgICAgICAgICAgIGZldGNoKCcvcHVibGljL2dldC1jb252ZXJzaW9uLXJlc3VsdCcsIHtcbiAgICAgICAgICAgICAgICBtZXRob2Q6ICdQT1NUJyxcbiAgICAgICAgICAgICAgICBib2R5OiBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgICAgICAgICAgICAgICdwcmltYXJ5Q3VycmVuY3knOiB0aGlzLnByaW1hcnlDdXJyZW5jeS52YWx1ZSxcbiAgICAgICAgICAgICAgICAgICAgJ3RhcmdldEN1cnJlbmN5JzogdGhpcy50YXJnZXRDdXJyZW5jeS52YWx1ZSxcbiAgICAgICAgICAgICAgICAgICAgYW1vdW50OiBwYXJzZUludCh0aGlzLmFtb3VudEZpZWxkLnZhbHVlKVxuICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIC50aGVuKHJlc3VsdCA9PiByZXN1bHQuanNvbigpKVxuICAgICAgICAgICAgICAgIC50aGVuKHJlc3VsdCA9PiBjb25zb2xlLmxvZyhyZXN1bHQpKTtcbiAgICAgICAgfSk7XG5cbiAgICB9XG5cbiAgICBzaG93Q29udmVyc2lvblJlc3VsdCgpIHtcbiAgICAgICAgY29uc29sZS5sb2codGhpcy5wcmltYXJ5Q3VycmVuY3kudmFsdWUpO1xuXG4gICAgfVxufVxuXG5jb25zdCBleGNoYW5nZSA9IG5ldyBFeGNoYW5nZSgpO1xuIl0sIm5hbWVzIjpbIkV4Y2hhbmdlIiwiZm9ybU5hbWUiLCJhbW91bnRGaWVsZCIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJwcmltYXJ5Q3VycmVuY3kiLCJ0YXJnZXRDdXJyZW5jeSIsImFkZEV2ZW50TGlzdGVuZXIiLCJmZXRjaCIsIm1ldGhvZCIsImJvZHkiLCJKU09OIiwic3RyaW5naWZ5IiwidmFsdWUiLCJhbW91bnQiLCJwYXJzZUludCIsInRoZW4iLCJyZXN1bHQiLCJqc29uIiwiY29uc29sZSIsImxvZyIsImV4Y2hhbmdlIl0sInNvdXJjZVJvb3QiOiIifQ==