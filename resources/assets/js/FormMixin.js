export default {
    data() {
        return {
            fields: {},
            metersData: {},
            stats: {
                annualStats: {hourly: 0, daily: 0, used: 0},
                monthStats: {hourly: 0, daily: 0, used: 0},
                weekStats: {hourly: 0, daily: 0, used: 0}
            },
            metersDiffDataData: {},
            errors: {},
            success: false,
            loaded: true,
            action: '',
            meters: [],
            meter: 0,
            showModal: true
        }
    },
    mounted() {
        this.getMeters()
        this.getMetersData();
    },
    methods: {
        getMeters: function () {
            axios.get('/api/getMeters')
                .then(function (response) {
                    this.meters = response.data;
                }.bind(this));
        },
        getMetersData: function (page = 1) {
            axios.get('/api/metersData?page=' + page)
                .then(function (response) {
                    this.metersData = response.data.vals;
                    this.metersDiffDataData = response.data.diffs;
                    this.stats = response.data.stats;
                }.bind(this));
        },
        update(val) {
            this.$emit('update', this.id, val.target.selectedOptions[0].value);
        },
        del(id) {
            axios.delete("/pond/meters", {params: {id: id}}).then(response => {
                window.location.href = '/pond/meters'
            });
        },
        disableReading(val) {
            axios.get('/api/getMeters')
                .then(function (response) {
                    this.meters = response.data;
                }.bind(this));
        },
        submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post(this.action, this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                    this.loaded = true;
                    this.success = true;
                    this.$emit('close');
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
                window.location.href = '/pond/meters'
            }
        },
    },
}