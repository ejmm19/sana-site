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
    },
    methods : {
        onDayClick(day) {
            console.log(day);
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
        proceedToPay() {
            checkout.open(function (result) {
                var transaction = result.transaction;
                console.log("Transaction ID: ", transaction.id);
                console.log("Transaction object: ", transaction);
            });
        }
    },
    mounted: function () {
        // this.daySelected = jQuery('.vc-day.is-today').find('.vc-day-content').attr('aria-label');
        this.employeeData = JSON.parse(jQuery('#content-calendar-app').attr('data-active-item'));
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
        },
        scheduleInfo: function (ini, set) {
            // console.log(ini, set);
        },
        itemIdPickerSelected: function (newValue, oldValue) {
            if (oldValue) {
                jQuery(".id-"+oldValue).removeClass('disabled-picker');
            }
            if (newValue) {
                jQuery(".id-"+newValue).addClass('disabled-picker');
            }
        }
    },
    computed: {
        timezone() {
            return 'America/Bogota';
        },
        disableForPay() {
            return this.timeStart === this.timeEnd;
        }
    }
});

let checkout = new WidgetCheckout({
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
})