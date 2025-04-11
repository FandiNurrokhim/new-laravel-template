"use strict";

$(function () {
    const selectPicker = $(".selectpicker"),
        select2 = $(".select2"),
        select2Icons = $(".select2-icons");

    if (selectPicker.length) {
        selectPicker.selectpicker();
    }

    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Choose your choice",
                dropdownParent: $this.parent(),
            });
        });
    }

    if (select2Icons.length) {
        function renderIcons(option) {
            if (!option.id) {
                return option.text;
            }
            var iconClass = $(option.element).data("icon");
            if (!iconClass) {
                return option.text;
            }
            var $icon =
                "<span><i class='" +
                iconClass +
                " me-2'></i>" +
                $.trim(option.text) +
                "</span>";
            return $icon;
        }
        select2Icons.wrap('<div class="position-relative"></div>').select2({
            templateResult: renderIcons,
            templateSelection: renderIcons,
            escapeMarkup: function (markup) {
                return markup;
            },
            dropdownParent: select2Icons.parent(),
        });
    }
});
