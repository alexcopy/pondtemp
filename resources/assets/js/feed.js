export default {
    data() {
        return {
            tankId: 0,
            ponds: [],

            feedIds: {
                pellets: [],
                sinking: []
            },

            feeds: {
                pellets: 0,
                sinking: 0
            },
            pondId: 'Pond',
            total: {},
            sortedTableData: {}
        }
    },
    mounted() {
        this.getFeeds(1);
    },
    methods: {
        getFeeds(days = 1) {
            axios.get('/api/getFeeds?days=' + days)
                .then(function (response) {
                    this.updateVars(response);
                }.bind(this));
        },

        feed(foodType) {
            axios.put('/pond/feed', {foodType: foodType, pond: this.pondId, scoops: 1})
                .then(function (response) {
                    this.updateVars(response);
                }.bind(this));
        },

        delFeed(foodType) {

            if (typeof (this.feedIds) == 'undefined' ||  this.feedIds === null)
                return null;

            if (this.feedIds[foodType].length > 0) {
                let id = this.feedIds[foodType].pop();
                axios.delete("/pond/feed", {params: {id: id}}).then(response => {
                    this.updateVars(response);
                });
            }
            return null;
        },

        updateVars(response) {
            this.feeds = response.data;
            this.ponds = response.data.ponds;
            this.total = response.data.tableData;
            this.feedIds = response.data.feedids;
            this.sortedTableData = this.total;
        }
    },

}