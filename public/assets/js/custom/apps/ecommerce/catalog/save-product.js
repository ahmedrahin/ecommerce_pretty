"use strict";

// Class definition
var KTAppEcommerceSaveProduct = function () {

    // Private functions

    // // Init quill editor
    // const initQuill = () => {
    //     // Define all elements for quill editor
    //     const elements = [
    //         '#kt_ecommerce_add_product_description',
    //         '#kt_ecommerce_add_long_description',
    //         '#kt_ecommerce_add_product_meta_description'
    //     ];

    //     // Loop all elements
    //     elements.forEach(element => {
    //         // Get quill element
    //         let quill = document.querySelector(element);

    //         // Break if element not found
    //         if (!quill) {
    //             return;
    //         }

    //         // Init quill --- more info: https://quilljs.com/docs/quickstart/
    //         quill = new Quill(element, {
    //             modules: {
    //                 toolbar: [
    //                     [{
    //                         header: [1, 2, false]
    //                     }],
    //                     ['bold', 'italic', 'underline'],
    //                     ['image', 'code-block']
    //                 ]
    //             },
    //             placeholder: 'Type your text here...',
    //             theme: 'snow' // or 'bubble'
    //         });
    //     });
    // }

    // // Init tagify
    // const initTagify = () => {
    //     // Define all elements for tagify
    //     const elements = [
    //         '#kt_ecommerce_add_product_category',
    //         '#kt_ecommerce_add_long_description',
    //         '#kt_ecommerce_add_product_tags'
    //     ];

    //     // Loop all elements
    //     elements.forEach(element => {
    //         // Get tagify element
    //         const tagify = document.querySelector(element);

    //         // Break if element not found
    //         if (!tagify) {
    //             return;
    //         }

    //         // Init tagify --- more info: https://yaireo.github.io/tagify/
    //         new Tagify(tagify, {
    //             whitelist: ["new", "trending", "sale", "discounted", "selling fast", "last 10"],
    //             dropdown: {
    //                 maxItems: 20,           // <- mixumum allowed rendered suggestions
    //                 classname: "tagify__inline__suggestions", // <- custom classname for this dropdown, so it could be targeted
    //                 enabled: 0,             // <- show suggestions on focus
    //                 closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
    //             }
    //         });
    //     });
    // }

    // // Init form repeater --- more info: https://github.com/DubFriend/jquery.repeater
    // const initFormRepeater = () => {
    //     $('#kt_ecommerce_add_product_options').repeater({
    //         initEmpty: false,

    //         defaultValues: {
    //             'text-input': 'foo'
    //         },

    //         show: function () {
    //             $(this).slideDown();

    //             // Init select2 on new repeated items
    //             initConditionsSelect2();
    //         },

    //         hide: function (deleteElement) {
    //             $(this).slideUp(deleteElement);
    //         }
    //     });
    // }

    // // Init condition select2
    // const initConditionsSelect2 = () => {
    //     // Tnit new repeating condition types
    //     const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-product="product_option"]');
    //     allConditionTypes.forEach(type => {
    //         if ($(type).hasClass("select2-hidden-accessible")) {
    //             return;
    //         } else {
    //             $(type).select2({
    //                 minimumResultsForSearch: -1
    //             });
    //         }
    //     });
    // }


    // // Init noUIslider
    // const initSlider = () => {
    //     var slider = document.querySelector("#kt_ecommerce_add_product_discount_slider");
    //     var value = document.querySelector("#kt_ecommerce_add_product_discount_label");

    //     noUiSlider.create(slider, {
    //         start: [10],
    //         connect: true,
    //         range: {
    //             "min": 1,
    //             "max": 100
    //         }
    //     });

    //     slider.noUiSlider.on("update", function (values, handle) {
    //         value.innerHTML = Math.round(values[handle]);
    //         if (handle) {
    //             value.innerHTML = Math.round(values[handle]);
    //         }
    //     });
    // }

    // // Init DropzoneJS --- more info:
    // const initDropzone = () => {
    //     var myDropzone = new Dropzone("#kt_ecommerce_add_product_media", {
    //         url: "https://keenthemes.com/scripts/void.php", // Set the url for your upload script location
    //         paramName: "file", // The name that will be used to transfer the file
    //         maxFiles: 10,
    //         maxFilesize: 10, // MB
    //         addRemoveLinks: true,
    //         accept: function (file, done) {
    //             if (file.name == "wow.jpg") {
    //                 done("Naha, you don't.");
    //             } else {
    //                 done();
    //             }
    //         }
    //     });
    // }

    // // Handle discount options
    // const handleDiscount = () => {
    //     const discountOptions = document.querySelectorAll('input[name="discount_option"]');
    //     const percentageEl = document.getElementById('kt_ecommerce_add_product_discount_percentage');
    //     const fixedEl = document.getElementById('kt_ecommerce_add_product_discount_fixed');

    //     discountOptions.forEach(option => {
    //         option.addEventListener('change', e => {
    //             const value = e.target.value;

    //             switch (value) {
    //                 case '2': {
    //                     percentageEl.classList.remove('d-none');
    //                     fixedEl.classList.add('d-none');
    //                     break;
    //                 }
    //                 case '3': {
    //                     percentageEl.classList.add('d-none');
    //                     fixedEl.classList.remove('d-none');
    //                     break;
    //                 }
    //                 default: {
    //                     percentageEl.classList.add('d-none');
    //                     fixedEl.classList.add('d-none');
    //                     break;
    //                 }
    //             }
    //         });
    //     });
    // }

  

    // Category status handler
    const handleStatus = () => {
        const target = document.getElementById('kt_ecommerce_add_product_status');
        const select = document.getElementById('kt_ecommerce_add_product_status_select');
        const statusClasses = ['bg-success', 'bg-warning', 'bg-danger'];

        $(select).on('change', function (e) {
            const value = e.target.value;

            switch (value) {
                case "1": {
                    target.classList.remove(...statusClasses);
                    target.classList.add('bg-success');
                    hideDatepicker();
                    break;
                }
                case "3": {
                    target.classList.remove(...statusClasses);
                    target.classList.add('bg-warning');
                    showDatepicker();
                    break;
                }
                case "0": {
                    target.classList.remove(...statusClasses);
                    target.classList.add('bg-danger');
                    hideDatepicker();
                    break;
                }
                case "2": {
                    target.classList.remove(...statusClasses);
                    target.classList.add('bg-primary');
                    hideDatepicker();
                    break;
                }
                default:
                    break;
            }
        });


        // Handle datepicker
        const datepicker = document.getElementById('kt_ecommerce_add_product_status_datepicker');

        // Init flatpickr --- more info: https://flatpickr.js.org/
        $('#kt_ecommerce_add_product_status_datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        const showDatepicker = () => {
            datepicker.parentNode.classList.remove('d-none');
        }

        const hideDatepicker = () => {
            datepicker.parentNode.classList.add('d-none');
        }

        $(select).trigger('change');
    }


    const datepicker = document.getElementById('kt_ecommerce_add_product_expire_datepicker');
    const datepicker2 = document.getElementById('kt_ecommerce_add_product_event_datepicker');

        // Init flatpickr --- more info: https://flatpickr.js.org/
        $('#kt_ecommerce_add_product_expire_datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        $('#kt_ecommerce_add_product_event_datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d",
        });

        const showDatepicker = () => {
            datepicker.parentNode.classList.remove('d-none');
        }

        const hideDatepicker = () => {
            datepicker.parentNode.classList.add('d-none');
        }

    

    // Public methods
    return {
        init: function () {
            // Init forms
            // initQuill();
            // initTagify();
            // initSlider();
            // initFormRepeater();
            // initDropzone();
            // initConditionsSelect2();

            // Handle forms
            handleStatus();
            // handleConditions();
            // handleDiscount();
            
            // handleSubmit();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSaveProduct.init();
});
