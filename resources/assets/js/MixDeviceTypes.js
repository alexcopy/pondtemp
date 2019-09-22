export default {
    data() {
        return {
            fields: {},
            errors: {},
            success: false,
            loaded: true,
            action: '',
            ponds:[],
            pond:0,
        }
    },
    mounted() {
        this.getPonds();
    },
    methods: {
        getPonds: function(){
            axios.get('/api/getPonds')
                .then(function (response) {
                    this.ponds = response.data;
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
            window.location.href = '/pond/types'
        },

    },
}