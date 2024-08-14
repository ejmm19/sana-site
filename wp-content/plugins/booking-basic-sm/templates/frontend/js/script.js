/*let checkout = new WidgetCheckout({
    currency: 'COP',
    amountInCents: 2490000,
    reference: 'AD002901221',
    publicKey: 'pub_fENJ3hdTJxdzs3hd35PxDBSMB4f85VrgiY3b6s1',
    signature: {integrity : '3a4bd1f3e3edb5e88284c8e1e9a191fdf091ef0dfca9f057cb8f408667f054d0'},
    redirectUrl: 'https://transaction-redirect.wompi.co/check', // Opcional
    expirationTime: '2023-06-09T20:28:50.000Z', // Opcional
    taxInCents: { // Opcional
        vat: 1900,
        consumption: 800
    },
    customerData: { // Opcional
        email:'lola@gmail.com',
        fullName: 'Lola Flores',
        phoneNumber: '3040777777',
        phoneNumberPrefix: '+57',
        legalId: '123456789',
        legalIdType: 'CC'
    },
    shippingAddress: { // Opcional
        addressLine1: "Calle 123 # 4-5",
        city: "Bogota",
        phoneNumber: '3019444444',
        region: "Cundinamarca",
        country: "CO"
    }
})*/
if (jQuery("#app").length > 0) {
    const start = new Date();
    new Vue({
        el: '#app',
        data: {
            selectedDate: null,
            fromPage: { month: start.getMonth()+1, year: start.getFullYear() },
            dateRange: {
                start: null,
                end: null
            },
            date: '',
            employeeData: '',
            validHours: function (hour, { weekday }) {
                const isSunday = weekday === 1;
                const isWeekday = weekday >= 2 && weekday <= 6;
                const isSaturday = weekday === 7;

                if (isWeekday) {
                    return hour >= 7 && hour <= 18;
                } else if (isSaturday) {
                    return hour >= 8 && hour <= 12;
                } else if (isSunday) {
                    return false;
                }
                return false;
            },
            selectDragAttribute: {
                popover: {
                    visibility: 'hover',
                    isInteractive: true,
                }
            },
            timeStart: '',
            timeEnd: '',
            scheduleInfo: '',
            daySelected: '',
            itemIdPickerSelected: '',
            scheduledTime: [],
            dateRangeScheduled: [],
            isDateValid: true,
            dateInvalidMsg: '',
            timezoneIndex: 0,
            timezones: [],
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
            msg: {
                email: '',
                phone: ''
            },
            dataOrder: {
                customerName: '',
                customerEmail: '',
                customerPhone: '',
                employeeId: '',
                date_init: '',
                date_finish: ''
            }
        },
        methods : {
            onDayClick(day) {
                this.itemIdPickerSelected = day.id;
                this.daySelected = this.formatDate(this.dateRange.start);
                this.selectedDate = this.dateRange.start;
                jQuery(day.event.target).trigger('click');
                // jQuery(day.event.target).addClass('disabled-picker');
            },
            formatTime(dateStr) {
                const date = new Date(dateStr);
                let hours = date.getHours();
                const minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12;
                hours = hours ? hours : 12; // La hora 0 debe ser 12
                const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

                return `${hours}:${formattedMinutes} ${ampm}`;
            },
            formatDateToMySQL(datetime) {
                const date = new Date(datetime);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');

                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            },
            formatDate(dateString) {
                const daysOfWeek = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
                const monthsOfYear = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                const date = new Date(dateString);
                const dayOfWeek = daysOfWeek[date.getDay()];
                const day = date.getDate();
                const month = monthsOfYear[date.getMonth()];
                const year = date.getFullYear();

                return `${dayOfWeek}, ${day} de ${month} de ${year}`;
            },
            isPastOrToday(dateString) {
                const inputDate = new Date(dateString);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                return inputDate <= today;
            },
            isLetter(e) {
                let char = String.fromCharCode(e.keyCode);
                if(/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/.test(char)) return true;
                else e.preventDefault();
            },
            validateEmail() {
                const email = this.dataOrder.customerEmail;
                if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
                    this.msg.email = 'Ingresa una dirección de correo válida';
                    this.dataOrder.customerEmail = '';
                } else {
                    this.msg.email = '';
                }
            },
            validatePhoneNumber() {
                const phone = this.dataOrder.customerPhone;
                if (!/^3\d{9}$/.test(phone)) {
                    this.msg.phone = 'Ingresa un número válido que comience con 3 y tenga 10 dígitos';
                    this.dataOrder.customerPhone = '';
                } else {
                    this.msg.phone = '';
                }
            },
            proceedToPay() {
                Swal.fire({
                    title: "¿ Desea realizar el agendamiento ?",
                    text: "A Continuación se procederá a realizar el agendamiento en nuestro sistema y en breve usted será contactado para realizar" +
                        "la confirmación de la misma y detalles.",
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    icon: "info"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        jQuery.ajax({
                            type: 'POST',
                            url: schedule_obj.ajax_url,
                            data: {
                                action: 'order_ajax_action',
                                security: schedule_obj.nonce,
                                handle: 'setOrder',
                                data: this.dataOrder
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: "Información guardada con éxito !",
                                        confirmButtonText: "Ok",
                                        icon: "success"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = '/agendamiento';
                                        }
                                    });
                                } else {
                                    alert('Ocurrió un error.');
                                }
                            }
                        });
                    }
                });
            },
            validateTime: function (start, end) {
                let startDate = new Date(start);
                let endDate = new Date(end);
                let isValid = true;
                let rangeBusy = '';
                for (let i = 0; i < this.dateRangeScheduled.length; i++) {
                    let element = this.dateRangeScheduled[i];
                    let initialDate = new Date(element.date_init);
                    let finalDate = new Date(element.date_finish);

                    if (startDate < finalDate && endDate > initialDate) {
                        isValid = false;
                        rangeBusy = this.formatTime(initialDate)+' a '+this.formatTime(finalDate);
                        break;
                    }
                }
                this.dateInvalidMsg = !isValid ? 'Agenda ocupada de '+rangeBusy+' por favor seleccione otro rango' : '';
                this.isDateValid = isValid;
            }
        },
        mounted: function () {
            // this.daySelected = jQuery('.vc-day.is-today').find('.vc-day-content').attr('aria-label');
            this.employeeData = JSON.parse(jQuery('#content-calendar-app').attr('data-active-item'));
            this.scheduledTime = this.employeeData.scheduled;
            // console.log(this.scheduledTime);
            this.dataOrder.employeeId = this.employeeData.id;
        },
        filters: {
            capitalize: function (value) {
                if (!value) return '';
                value = value.toString();
                return value.charAt(0).toUpperCase() + value.slice(1);
            }
        },
        watch: {
            dateRange: function (data) {
                this.timeStart = this.formatTime(data.start);
                this.timeEnd = this.formatTime(data.end);
                this.dataOrder.date_init = this.formatDateToMySQL(data.start);
                this.dataOrder.date_finish = this.formatDateToMySQL(data.end);
                this.validateTime(this.formatDateToMySQL(data.start), this.formatDateToMySQL(data.end));
            },
            itemIdPickerSelected: function (newValue, oldValue) {
                if (oldValue) {
                    jQuery(".id-"+oldValue).removeClass('disabled-picker');
                }
                if (newValue) {
                    jQuery(".id-"+newValue).addClass('disabled-picker');
                }
                let dateRage = [];
                this.scheduledTime.forEach((el, i) => {
                    if (el.schedule_date === newValue) {
                        dateRage.push({date_init: el.date_init, date_finish: el.date_finish});
                    }
                });
                this.dateRangeScheduled = dateRage;
                console.log(dateRage);
                this.dataOrder.schedule_date = newValue;
            }
        },
        computed: {
            timezone() {
                return 'America/Bogota';
            },
            disableForPay() {
                return this.timeStart === this.timeEnd || this.dataOrder.customerName === ''
                    || this.dataOrder.customerEmail === '' || this.dataOrder.customerPhone === '' || this.msg.email !== ''
                    || this.msg.phone !== '' || this.isDateValid === false;
            }
        }
    });
}
