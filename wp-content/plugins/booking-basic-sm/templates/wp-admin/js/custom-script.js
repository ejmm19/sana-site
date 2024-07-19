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
