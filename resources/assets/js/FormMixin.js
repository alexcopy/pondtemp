export default {
    data() {
        return {
            fields: {},
            errors: {},
            success: false,
            loaded: true,
            action: '',
            meters:[],
            meter:0
        }
    },
    mounted() {
        this.getMeters();
    },
    methods: {
        getMeters: function(){
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
            }
        },

    },
}