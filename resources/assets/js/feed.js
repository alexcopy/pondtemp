export default {
    data() {
        return {
            tankId: 0,
            ponds: [],
            feeds: {
                pellets: 0,
                sinking: 0
            },
            pondId: 'pond',
            total: {},
        }
    },
    mounted() {
        this.getFeeds(1);
    },
    methods: {
        getFeeds(days = 1) {
            axios.get('/api/getFeeds?days=' + days)
                .then(function (response) {
                    this.feeds = response.data;
                    this.ponds = response.data.ponds;
                    this.total = response.data.total;
                }.bind(this));
        },

        feed(foodType) {
            axios.put('/pond/feed', {foodType: foodType, pond: this.pondId, scoops: 1})
                .then(function (response) {
                    this.feeds = response.data;
                    this.ponds = response.data.ponds;
                    this.total = response.data.total;
                }.bind(this));
        },
    },

}