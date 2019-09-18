export default {
    data() {
        return {
            fields: {},
            metersData: {},
            stats: {
                annualStats:['hourly','daily', 'used'],
                monthStats:['hourly','daily', 'used'],
                weekStats:['hourly','daily', 'used']
            },
            metersDiffDataData: {},
            errors: {},
            success: false,
            loaded: true,
            action: '',
            meters:[],
            meter:0,
            showModal: true
        }
    },
    mounted() {
        this.getMeters()
        this.getMetersData();
    },
    methods: {
        getMeters: function(){
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
        del() {
            this.$emit('delete', this.id);
        },
        disableReading(val){
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