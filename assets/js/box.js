(function () {
  'use strict';

  function bindBox(box) {
    if (!box || box.getAttribute('data-bw-box-ready') === '1') {
      return;
    }
    box.setAttribute('data-bw-box-ready', '1');

    var enter = function () {
      box.classList.add('is-hovered');
    };
    var leave = function () {
      box.classList.remove('is-hovered');
    };

    box.addEventListener('mouseenter', enter);
    box.addEventListener('mouseleave', leave);
    box.addEventListener('focusin', enter);
    box.addEventListener('focusout', function (event) {
      if (!box.contains(event.relatedTarget)) {
        leave();
      }
    });

    // Touch devices: first tap reveals hover state without fighting the link.
    box.addEventListener('touchstart', function () {
      if (!box.classList.contains('is-hovered')) {
        enter();
      }
    }, { passive: true });
  }

  function initBoxes(root) {
    var scope = root && root.querySelectorAll ? root : document;
    var boxes = scope.querySelectorAll('.bw-hover-box');
    boxes.forEach(bindBox);
  }

  function onElementorReady($scope) {
    var el = $scope && $scope[0] ? $scope[0] : document;
    initBoxes(el);
  }

  if (typeof jQuery !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function () {
      if (window.elementorFrontend && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/b_box.default', onElementorReady);
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initBoxes(document);
    });
  } else {
    initBoxes(document);
  }
})();
