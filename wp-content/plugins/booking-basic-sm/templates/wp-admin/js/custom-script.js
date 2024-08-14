if (jQuery("#app-calendar").length) {
    const start = new Date();
    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth();
    new Vue({
        el: '#app-calendar',
        data: {
            employees: null,
            employee: '',
            selectedDate: null,
            dateRange: {},
            fromPage: { month: start.getMonth()+1, year: start.getFullYear() },
            attrs: [
                {
                    key: 'today',
                    highlight: {
                        color: 'purple',
                        fillMode: 'solid',
                        contentClass: 'italic',
                    },
                    //dates: new Date(year, month, 17),
                },
                /*{
                    highlight: {
                        color: 'purple',
                        fillMode: 'light',
                    },
                    dates: new Date(year, month, 19),
                },
                {
                    highlight: {
                        color: 'purple',
                        fillMode: 'outline',
                    },
                    dates: [
                        new Date(year, month, 27),
                        new Date(year, month, 28)
                    ],
                },*/
            ]
        },
        methods : {
            submitForm : function (e) {
                console.log(e.target);
                let form = jQuery(e.target).find('form');
                form.submit();
                console.log(form);
            }
        }
    });
    /*
    const start = new Date();
    const end = new Date(2024, 6, 17);
    new Vue({
        el: '#app',
        data: {
            selectedDate: null,
            fromPage: {month: start.getMonth()+1, year: start.getFullYear() },
            dateRange: {
                start,
                end: start
            },
            validHours: function (hour, { weekday }) {
                const isWeekday = weekday >= 1 && weekday <= 5;
                const isWeekend = !isWeekday;
                const isWeekdayHours = hour >= 7 && hour <= 18;
                const isWeekendHours = hour >= 8 && hour <= 12;
                return (isWeekday && isWeekdayHours) || (isWeekend && isWeekendHours);
            },
            timezoneIndex: 0,
            timezones: [],
            range: false,
            attrs: [
                {
                    key: 'today',
                    highlight: {
                        color: 'orange',
                        fillMode: 'dark',
                    },
                    dates: new Date(),
                    value: start
                },
            ],
        },
        methods : {
            onDayClick(day) {
                console.log(day);
                this.dateRange
            }
        },
        computed: {
            timezone() {
                return 'America/Bogota';
            },
        }
    });*/
}

if (jQuery("#app-settings").length > 0) {
    new Vue({
        el: "#app-settings",
        data: {
            agencyEmails: [],
            agencyPhones: [],
            baseValuePerHour: null,
            whatsAppNumber: null,
            form: {
                agencyEmails: '',
                agencyPhones: '',
                baseValuePerHour: '',
                whatsAppNumber: ''
            }
        },
        methods: {
            setAgencyEmails: function () {
                let correos = this.form.agencyEmails;
                const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const correosValidos = correos
                    .split(',')
                    .map(correo => correo.trim())
                    .filter(correo => regexEmail.test(correo));

                this.form.agencyEmails = correosValidos.join(',');
            },
            validatePhone() {
                let phones = this.form.agencyPhones;
                const regexPhone = /^3\d{9}$/;
                const phoneList = phones.split(',');

                const validPhones = phoneList
                    .map(phone => phone.trim())
                    .filter(phone => regexPhone.test(phone));

                this.form.agencyPhones = validPhones.join(',');
            },
            formatNumber() {
                let cleanedValue = this.form.baseValuePerHour.replace(/\./g, '');
                this.form.baseValuePerHour = cleanedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            },
            validateWhatsApp: function () {
                let whatsApp = this.form.whatsAppNumber;
                const regexWhatsApp = /^3\d{9}$/;
                const whatsAppClean = whatsApp.trim();
                if (regexWhatsApp.test(whatsAppClean)) {
                    this.form.whatsAppNumber = whatsAppClean;
                } else {
                    this.form.whatsAppNumber = '';
                }
            },
            saveConfig: function (event) {
                console.log(this.form);
                alertAndSendData(
                    this.form,
                    "setMultipleConfig",
                    "Desea aprobar esta agenda !",
                    "Si, Aprobar",
                    "No",
                    "info"
                );
            }
        },
        computed: {
            validForm() {
                return this.form.agencyEmails === '' || this.form.agencyPhones === '' || this.form.baseValuePerHour === ''
                    || this.form.whatsAppNumber === '';
            }
        },
        mounted(){
            this.form.agencyEmails = document.querySelector('#agencyEmails').getAttribute('data-item-value');
            this.form.agencyPhones = document.querySelector('#agencyPhones').getAttribute('data-item-value');
            this.form.baseValuePerHour = document.querySelector('#baseValuePerHour').getAttribute('data-item-value');
            this.form.whatsAppNumber = document.querySelector('#whatsAppNumber').getAttribute('data-item-value');
        }
    });
}

if (jQuery("#app-order-data").length > 0) {
    new Vue({
        el: "#app-order-data",
        data: { },
        methods: {
            approveItem: function (id) {
                console.log(id);
                alertAndSendData(
                    {id: id},
                    "approveOrder",
                    "Desea aprobar esta agenda !",
                    "Si, Aprobar",
                    "No",
                    "info"
                );
            },
            editItem: function (id) {
                console.log(id);
            },
            deleteItem: function (id) {
                alertAndSendData(
                    {id: id},
                    "deleteOrder",
                    "Desea eliminar el registro !",
                    "Si, eliminar",
                    "No",
                    "info"
                );
            }
        }
    });
}

function alertAndSendData (data, handle, title, confirmButtonText, denyButtonText, icon) {
    Swal.fire({
        title: title,
        showDenyButton: true,
        confirmButtonText: confirmButtonText,
        denyButtonText: denyButtonText,
        icon: icon
    }).then((result) => {
        if (result.isConfirmed) {
            jQuery.ajax({
                type: 'POST',
                url: schedule_obj.ajax_url,
                data: {
                    action: 'order_ajax_action',
                    security: schedule_obj.nonce,
                    handle: handle,
                    data: data
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Información guardada con éxito !",
                            confirmButtonText: "Ok",
                            icon: "success"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        alert('Ocurrió un error.');
                    }
                }
            });
        }
    });
}